<?php

/**
 * Reservations Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reservations
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\DataTables\ReservationsDataTable;
use App\Models\Reservation;
use App\Models\ProfilePicture;
use App\Models\PaymentGateway;
use App\Models\Payouts;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use Validator;

class ReservationsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Reservations
     *
     * @param array $dataTable  Instance of ReservationsDataTable
     * @return datatable
     */
    public function index(ReservationsDataTable $dataTable)
    {
        return $dataTable->render('admin.reservations.view');
    }

    /**
     * Detailed Reservation
     *
     * @param array $request    Input values
     * @return redirect     to Reservation View
     */
    public function detail(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = $result = Reservation::find($request->id);
            $payouts = Payouts::whereReservationId($request->id)->whereUserType('host')->first();
            
            $data['penalty_amount'] = @$payouts->total_penalty_amount;
            
            return view('admin.reservations.detail', $data);
        }
    }

    /**
     * Delete Reservations
     *
     * @param array $request    Input values
     * @return redirect     to Reservation View
     */
    public function delete(Request $request)
    {
        Reservation::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/reservations');
    }

    /**
     * Amount Transfer to Guest and Host
     *
     * @param array $request    Input values
     * @return redirect     to Reservation View
     */
    public function payout(Request $request, EmailController $email_controller)
    {
        $reservation_id = $request->reservation_id;
        $reservation_details = Reservation::find($reservation_id);

        if($request->user_type == 'host')
        {
            $payout_email_id = $reservation_details->host_payout_email_id;
            $amount = $this->payment_helper->currency_convert($reservation_details->currency_code, 'EUR', $reservation_details->host_payout);
            $payout_user_id = $reservation_details->host_id;
            $payout_preference_id = $reservation_details->host_payout_preference_id;
            $payout_id = $request->host_payout_id;
        }

        if($request->user_type == 'guest')
        {
            $payout_email_id = $reservation_details->guest_payout_email_id;
            $amount = $this->payment_helper->currency_convert($reservation_details->currency_code, 'EUR', $reservation_details->guest_payout);
            $payout_user_id = $reservation_details->user_id;
            $payout_preference_id = $reservation_details->guest_payout_preference_id;
            $payout_id = $request->guest_payout_id;
        }
        
            // Set request-specific fields.
            $vEmailSubject = 'PayPal payment';
            $emailSubject  = urlencode($vEmailSubject);
            $receiverType  = urlencode($payout_email_id);
            $currency      = urlencode('EUR'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

            // Receivers
            // Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
            // Here you can modify to obtain array data from database.

            $receivers = array(
                            0 => array(
                                    'receiverEmail' => "$payout_email_id", 
                                    'amount' => "$amount",
                                    'uniqueID' => "$reservation_id", // 13 chars max
                                    'note' => " payment of commissions"
                                )
                        );

            $receiversLenght = count($receivers);

            // Add request-specific fields to the request string.
            $nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

            $receiversArray = array();

            for($i = 0; $i < $receiversLenght; $i++)
            {
                $receiversArray[$i] = $receivers[$i];
            }

            foreach($receiversArray as $i => $receiverData)
            {
                $receiverEmail = urlencode($receiverData['receiverEmail']);
                $amount = urlencode($receiverData['amount']);
                $uniqueID = urlencode($receiverData['uniqueID']);
                $note = urlencode($receiverData['note']);
                $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
            }

            // Execute the API operation; see the PPHttpPost function above.
            $httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
            //echo $nvpStr;exit;

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
            {
                $payouts = Payouts::find($payout_id);

                $payouts->reservation_id       = $reservation_id;
                $payouts->room_id              = $reservation_details->room_id;
                $payouts->correlation_id       = $httpParsedResponseAr['CORRELATIONID'];
                $payouts->amount               = $amount;
                $payouts->currency_code        = $currency;
                $payouts->user_type            = $request->user_type;
                $payouts->user_id              = $payout_user_id;
                $payouts->account              = $payout_email_id;
                $payouts->status               = 'Completed';

                $payouts->save();

                $email_controller->payout_sent($reservation_id, $request->user_type);

                $this->helper->flash_message('success', ucfirst($request->user_type).' payout amount has transferred successfully'); // Call flash message function
                return redirect('admin/reservation/detail/'.$reservation_id);
            }
            else
            {
                exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
            }
    }

    public function need_payout_info(Request $request, EmailController $email_controller)
    {
        $type = $request->type;
        $email_controller->need_payout_info($request->id, $type);

        $this->helper->flash_message('success', 'Email sent Successfully'); // Call flash message function
        return redirect('admin/reservation/detail/'.$request->id);
    }

    /**
     * Core function for Amount Transfer from PayPal
     *
     * @param array $request    Input values
     * @return response
     */
    public function PPHttpPost($methodName_, $nvpStr_)
    {
        global $environment;

        $paypal_credentials = PaymentGateway::where('site','PayPal')->get();
 
        $api_user = $paypal_credentials[0]->value;
        $api_pwd  = $paypal_credentials[1]->value;
        $api_key  = $paypal_credentials[2]->value;
        $paymode  = $paypal_credentials[3]->value;

        if($paymode == 'sandbox')
            $environment = 'sandbox';
        else
            $environment = '';
      
        // Set up your API credentials, PayPal end point, and API version.
        // How to obtain API credentials:
        // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO
        $API_UserName = urlencode($api_user);
        $API_Password = urlencode($api_pwd);
        $API_Signature = urlencode($api_key);
        $API_Endpoint = "https://api-3t.paypal.com/nvp";

        if("sandbox" === $environment || "beta-sandbox" === $environment)
            $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
        
        $version = urlencode('51.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if(!$httpResponse)
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value)
        {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1)
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
        }

        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");

        return $httpParsedResponseAr;
    }
}
