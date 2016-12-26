<?php 

/**
 * Payment Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Payment
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Rooms;
use App\Models\Currency;
use App\Models\Country;
use App\Models\PaymentGateway;
use App\Models\Reservation;
use App\Models\Calendar;
use App\Models\Messages;
use App\Models\Payouts;
use App\Models\CouponCode;
use App\Models\Referrals;
use App\Models\AppliedTravelCredit;
use Validator;
use App\Http\Helper\PaymentHelper;
use App\Http\Controllers\EmailController;
use App\Http\Start\Helpers;
use DateTime;
use Session;
use Auth;

class PaymentController extends Controller 
{
    protected $omnipay; // Global variable for Omnipay instance

    protected $payment_helper; // Global variable for Helpers instance
    
    /**
     * Constructor to Set PaymentHelper instance in Global variable
     *
     * @param array $payment   Instance of PaymentHelper
     */
    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Setup the Omnipay PayPal API credentials
     *
     * @param string $gateway  PayPal Payment Gateway Method as PayPal_Express/PayPal_Pro
     * PayPal_Express for PayPal account payments, PayPal_Pro for CreditCard payments
     */
    public function setup($gateway = 'PayPal_Express')
    {
        // Get PayPal credentials from payment_gateway table
        $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();

        // Create the instance of Omnipay
        $this->omnipay  = Omnipay::create($gateway);

        $this->omnipay->setUsername($paypal_credentials[0]->value);
        $this->omnipay->setPassword($paypal_credentials[1]->value);
        $this->omnipay->setSignature($paypal_credentials[2]->value);
        $this->omnipay->setTestMode(($paypal_credentials[3]->value == 'sandbox') ? true : false);
        if($gateway == 'PayPal_Express')
            $this->omnipay->setLandingPage('Login');
    }

    /**
     * Load Payment view file
     *
     * @param $request  Input values
     * @return payment page view
     */
    public function index(Request $request)
    {
        $special_offer_id = '';

        if(!$_POST && !$_GET)
        {
            $id               = Session::get('payment_room_id');
            $checkin          = Session::get('payment_checkin');
            $checkout         = Session::get('payment_checkout');
            $number_of_guests = Session::get('payment_number_of_guests');
            $booking_type     = Session::get('payment_booking_type');
        }
        elseif($_POST)
        {
            Session::put('payment_room_id', $request->id);
            Session::put('payment_checkin', $request->checkin);
            Session::put('payment_checkout', $request->checkout);
            Session::put('payment_number_of_guests', $request->number_of_guests);
            Session::put('payment_booking_type', $request->booking_type);
            
            $id               = Session::get('payment_room_id');
            $checkin          = Session::get('payment_checkin');
            $checkout         = Session::get('payment_checkout');
            $number_of_guests = Session::get('payment_number_of_guests');
            $booking_type     = Session::get('payment_booking_type');
        }
        else
        {
            Session::put('payment_room_id', $request->room_id);
            Session::put('payment_checkin', date('d-m-Y', strtotime($request->checkin)));
            Session::put('payment_checkout', date('d-m-Y', strtotime($request->checkout)));
            Session::put('payment_number_of_guests', $request->number_of_guests);
            Session::put('payment_booking_type', 'instant_book');
            Session::put('payment_special_offer_id', $request->special_offer_id);
            
            $id               = Session::get('payment_room_id');
            $checkin          = Session::get('payment_checkin');
            $checkout         = Session::get('payment_checkout');
            $number_of_guests = Session::get('payment_number_of_guests');
            $booking_type     = Session::get('payment_booking_type');
            $special_offer_id = Session::get('payment_special_offer_id');
        }
        
        if(!$_POST && !$checkin)
        {
            return redirect('rooms/'.$request->id);
        }

        $data['result']           = Rooms::find($id);
        $data['room_id']          = $id;
        
        $data['checkin']          = $checkin;
        $data['checkout']         = $checkout;
        $data['number_of_guests'] = $number_of_guests;
        $data['booking_type']     = $booking_type;
        
        $from                     = new DateTime($checkin);
        $to                       = new DateTime($checkout);
        
        $data['nights']           = $to->diff($from)->format("%a");

        $travel_credit_result = Referrals::whereUserId(Auth::user()->user()->id)->get();
        $travel_credit_friend_result = Referrals::whereFriendId(Auth::user()->user()->id)->get();

        $travel_credit = 0;
        
        foreach($travel_credit_result as $row) {
            $travel_credit += $row->credited_amount;
        }
        
        foreach($travel_credit_friend_result as $row) {
            $travel_credit += $row->friend_credited_amount;
        }
        
        if($travel_credit && Session::get('remove_coupon') != 'yes' && Session::get('manual_coupon') != 'yes') {
            Session::put('coupon_code', 'Travel_Credit');
            Session::put('coupon_amount', $travel_credit);
        }

        $data['price_list']       = json_decode($this->payment_helper->price_calculation($data['room_id'], $data['checkin'], $data['checkout'], $data['number_of_guests'], $special_offer_id));
        
        Session::put('payment_price_list', $data['price_list']);

        if(@$data['price_list']->status == 'Not available')
        {
            $this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
            return redirect('rooms/'.$id);
        }

        $data['price_eur']        = $this->payment_helper->currency_convert($data['result']->rooms_price->code, 'USD', $data['price_list']->total);

        $data['price_rate']       = $this->payment_helper->currency_rate($data['result']->rooms_price->currency_code, 'USD');
        
        // Get First Default Currency from currency table
        $data['currency']         = Currency::where('default_currency', 1)->take(1)->get();
        $data['country']          = Country::all()->lists('long_name', 'short_name');

        return view('payment.payment', $data);
    }

    /**
     * Appy Coupen Code Function
     *
     * @param array $request    Input values
     * @return redirect to Payemnt Page
     */
    public function apply_coupon(Request $request)
    {
        $coupon_code      = $request->coupon_code;
        $result           = CouponCode::where('coupon_code', $coupon_code)->get();
        $interval         = "Check_Expired_coupon";

        if($result->count())
        {
            $datetime1 = new DateTime(); 
            $datetime2 = new DateTime($result[0]->expired_at);

            if($datetime1 < $datetime2)
            {
                $interval_diff = $datetime1->diff($datetime2);
                $interval      = $interval_diff->days;  
            }
            else
            {
                $interval = "Expired_coupon"; 
            } 
        }

        if($interval != "Expired_coupon" && $interval != "Check_Expired_coupon")
        {
            $id               = Session::get('payment_room_id');
            $price_list       = Session::get('payment_price_list');
            $code             = Session::get('currency');

            $data['coupon_amount']  = $this->payment_helper->currency_convert($result[0]->currency_code,$code,$result[0]->amount);

            if($price_list->total > $data['coupon_amount'])
            {
                $data['coupen_applied_total']  = $price_list->total - $data['coupon_amount'];

                Session::forget('coupon_code');
                Session::forget('coupon_amount');
                Session::forget('remove_coupon');
                Session::forget('manual_coupon');
                Session::put('coupon_code', $coupon_code);
                Session::put('coupon_amount', $data['coupon_amount']);
                Session::put('manual_coupon', 'yes');
            }
            else
            {
                $data['message']  = trans('messages.payments.big_coupon'); ; 
            }
        }
        else
        { 
            if($interval == "Expired_coupon")
            {
                $data['message']  = trans('messages.payments.expired_coupon');  
            }
            else
            {
                $data['message']  = trans('messages.payments.invalid_coupon');     
            }
        }

        return json_encode($data);
    }

    public function remove_coupon(Request $request)
    {
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('manual_coupon');
        Session::put('remove_coupon', 'yes');
    }

    /**
     * Payment Submit Function
     *
     * @param array $request    Input values
     * @return redirect to Dashboard Page
     */
	public function create_booking(Request $request)
	{
        // Get PayPal credentials from payment_gateway table
        $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();
        
        $price_list     = json_decode($this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->number_of_guests));

        $amount         = $this->payment_helper->currency_convert($request->currency, 'USD', $price_list->total);

        $country = $request->payment_country;

        $message_to_host = $request->message_to_host;


        $purchaseData   =   [
                            'testMode'  => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
                            'amount'    => $amount,
                            'currency'  => 'USD',
                            'returnUrl' => url('payments/success'),
                            'cancelUrl' => url('payments/cancel')
                            ];

        Session::put('amount', $amount);
        Session::put('payment_country', $country);
        Session::put('message_to_host_'.Auth::user()->user()->id, $message_to_host);
        
        Session::save();
        
        if($request->payment_method == 'cc')
        {
            $rules =    [
                        'cc_number'        => 'required|numeric|digits_between:12,19|validateluhn',
                        'cc_expire_month'  => 'required|expires:cc_expire_month,cc_expire_year',
                        'cc_expire_year'   => 'required|expires:cc_expire_month,cc_expire_year',
                        'cc_security_code' => 'required',
                        'first_name'       => 'required',
                        'last_name'        => 'required',
                        'zip'              => 'required',
                        ];

            $niceNames =    [
                            'cc_number'        => 'Card number',
                            'cc_expire_month'  => 'Expires',
                            'cc_expire_year'   => 'Expires',
                            'cc_security_code' => 'Security code',
                            'first_name'       => 'First name',
                            'last_name'        => 'Last name',
                            'zip'              => 'Postal code',
                            ];

            $messages =     [
                            'expires'      => 'Card has expired',
                            'validateluhn' => 'Card number is invalid'
                            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }

            $this->setup('PayPal_Pro');

            $card   =   [
                    'firstName'       => $request->first_name,
                    'lastName'        => $request->last_name,
                    'number'          => $request->cc_number, //5555555555554444
                    'expiryMonth'     => $request->cc_expire_month, //01
                    'expiryYear'      => $request->cc_expire_year, //2020
                    'cvv'             => $request->cc_security_code, //123
                    'billingAddress1' => $request->payment_country,
                    'billingCountry'  => $request->payment_country,
                    'billingCity'     => $request->payment_country,
                    'billingPostcode' => $request->zip,
                    'billingState'    => $request->payment_country
                    ];

            $purchaseData['card'] = $card;
        }
        else
            $this->setup();    

        if($amount) {

        $response = $this->omnipay->purchase($purchaseData)->send();

        // Process response
        if ($response->isSuccessful())
        {
            // Payment was successful
            $result = $response->getData();

            $data = [
                    'room_id'          => $request->room_id,
                    'checkin'          => $request->checkin,
                    'checkout'         => $request->checkout,
                    'number_of_guests' => $request->number_of_guests,
                    'transaction_id'   => $result['TRANSACTIONID'],
                    'price_list'       => $price_list,
                    'paymode'          => 'Credit Card',
                    'first_name'       => $request->first_name,
                    'last_name'        => $request->last_name,
                    'postal_code'      => $request->zip,
                    'country'          => $request->payment_country
                    ];
            
            $code = $this->store($data);
            
            $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
            return redirect('reservation/requested?code='.$code);
        } 
        elseif ($response->isRedirect()) 
        {
            // Redirect to offsite payment gateway
            $response->redirect();
        } 
        else 
        {
            // Payment failed
            $this->helper->flash_message('error', $response->getMessage()); // Call flash message function
            return redirect('payments/book/'.$request->room_id);
        }
        }
        else {
            $data = [
                    'room_id'          => $request->room_id,
                    'checkin'          => $request->checkin,
                    'checkout'         => $request->checkout,
                    'number_of_guests' => $request->number_of_guests,
                    'transaction_id'   => '',
                    'price_list'       => $price_list,
                    'paymode'          => ($request->payment_method == 'cc') ? 'Credit Card' : 'PayPal',
                    'first_name'       => $request->first_name,
                    'last_name'        => $request->last_name,
                    'postal_code'      => $request->zip,
                    'country'          => $request->payment_country
                    ];
            
            $code = $this->store($data);
            
            $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
            return redirect('reservation/requested?code='.$code);
        }
	}

    /**
     * Callback function for Payment Success
     *
     * @param array $request    Input values
     * @return redirect to Payment Success Page
     */
	public function success(Request $request)
	{
        $this->setup();

        $transaction = $this->omnipay->completePurchase(array(
            'payer_id'              => $request->PayerID,
            'transactionReference'  => $request->token,
            'amount'                => Session::get('amount'),
            'currency'              => 'USD'
        ));

        $response = $transaction->send();

        $result = $response->getData();

        if(@$result['ACK'] == 'Success')
        {
            $data = [
                'room_id'          => Session::get('payment_room_id'),
                'checkin'          => Session::get('payment_checkin'),
                'checkout'         => Session::get('payment_checkout'),
                'number_of_guests' => Session::get('payment_number_of_guests'),
                'transaction_id'   => @$result['PAYMENTINFO_0_TRANSACTIONID'],
                'price_list'       => Session::get('payment_price_list'),
                'country'          => Session::get('payment_country'),
                'message_to_host'  => Session::get('message_to_host_'.Auth::user()->user()->id),
                'paymode'          => 'PayPal'
                ];
        
            $code = $this->store($data);

            $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
            return redirect('reservation/requested?code='.$code);
        }
        else
        {
            dd($result);
            // Payment failed
            $this->helper->flash_message('error', $result['L_SHORTMESSAGE0']); // Call flash message function
            return redirect('payments/book/'.Session::get('payment_room_id'));
        }
	}

    /**
     * Callback function for Payment Failed
     *
     * @param array $request    Input values
     * @return redirect to Payments Booking Page
     */
	public function cancel(Request $request)
	{
        // Payment failed
        $this->helper->flash_message('error', $response->getMessage()); // Call flash message function
        return redirect('payments/book/'.$id);
	}

    /**
     * Create Reservation After Payment Successfully Done
     *
     * @param array $data    Payment Data
     * @return string $code  Reservation Code
     */
    public function store($data)
    {
        
        $reservation = new Reservation;

        $reservation->room_id           = $data['room_id'];
        $reservation->host_id           = Rooms::find($data['room_id'])->user_id;
        $reservation->user_id           = Auth::user()->user()->id;
        $reservation->checkin           = $this->payment_helper->date_convert($data['checkin']);
        $reservation->checkout          = $this->payment_helper->date_convert($data['checkout']);
        $reservation->number_of_guests  = $data['number_of_guests'];
        $reservation->nights            = $data['price_list']->total_nights;
        $reservation->per_night         = $data['price_list']->rooms_price;
        $reservation->subtotal          = $data['price_list']->subtotal;
        $reservation->cleaning          = $data['price_list']->cleaning_fee;
        $reservation->additional_guest  = $data['price_list']->additional_guest;
        $reservation->security          = $data['price_list']->security_fee;
        $reservation->service           = $data['price_list']->service_fee;
        $reservation->host_fee          = $data['price_list']->host_fee;
        $reservation->total             = $data['price_list']->total;
        $reservation->currency_code     = $data['price_list']->currency;

        if($data['price_list']->coupon_amount)
        {
          $reservation->coupon_code       = $data['price_list']->coupon_code;
          $reservation->coupon_amount     = $coupon_amount = $data['price_list']->coupon_amount;
        }
        
        $reservation->transaction_id    = $data['transaction_id'];
        $reservation->paymode           = $data['paymode'];
        $reservation->cancellation      = Rooms::find($data['room_id'])->cancel_policy;
        $reservation->type              = 'reservation';
        
        if($data['paymode'] == 'Credit Card')
        {
            $reservation->first_name   = $data['first_name'];
            $reservation->last_name    = $data['last_name'];
            $reservation->postal_code  = $data['postal_code'];
        }
        
        $reservation->country          = $data['country'];
        $reservation->status           = (Session::get('payment_booking_type') == 'instant_book') ? 'Accepted' : 'Pending';

        $reservation->save();

          if(@$data['price_list']->coupon_code == 'Travel_Credit') {
            $referral_friend = Referrals::whereFriendId(Auth::user()->user()->id)->get();
            foreach($referral_friend as $row) {
                $friend_credit = $row->friend_credited_amount;
                if($coupon_amount != 0) {
                if($friend_credit <= $coupon_amount) {
                    $referral = Referrals::find($row->id);
                    $referral->friend_credited_amount = 0;
                    $referral->save();
                    $coupon_amount = $coupon_amount - $friend_credit;

                    $applied_referral = new AppliedTravelCredit;
                    $applied_referral->reservation_id = $reservation->id;
                    $applied_referral->referral_id = $row->id;
                    $applied_referral->amount = $friend_credit;
                    $applied_referral->type = 'friend';
                    $applied_referral->currency_code = $data['price_list']->currency;
                    $applied_referral->save();                    
                }
                else {
                    $referral = Referrals::find($row->id);
                    $referral->friend_credited_amount = $friend_credit - $coupon_amount;
                    $referral->save();
                    
                    $applied_referral = new AppliedTravelCredit;
                    $applied_referral->reservation_id = $reservation->id;
                    $applied_referral->referral_id = $row->id;
                    $applied_referral->amount = $coupon_amount;
                    $applied_referral->type = 'friend';
                    $applied_referral->currency_code = $data['price_list']->currency;
                    $applied_referral->save();
                    $coupon_amount = 0;
                }
                }
            }
            $referral_user = Referrals::whereUserId(Auth::user()->user()->id)->get();
            foreach($referral_user as $row) {
                $user_credit = $row->credited_amount;
                if($coupon_amount != 0) {
                if($user_credit <= $coupon_amount) {
                    $referral = Referrals::find($row->id);
                    $referral->credited_amount = 0;
                    $referral->save();
                    $coupon_amount = $coupon_amount - $user_credit;
                    
                    $applied_referral = new AppliedTravelCredit;
                    $applied_referral->reservation_id = $reservation->id;
                    $applied_referral->referral_id = $row->id;
                    $applied_referral->amount = $user_credit;
                    $applied_referral->type = 'main';
                    $applied_referral->currency_code = $data['price_list']->currency;
                    $applied_referral->save();
                }
                else {
                    $referral = Referrals::find($row->id);
                    $referral->credited_amount = $user_credit - $coupon_amount;
                    $referral->save();
                    
                    $applied_referral = new AppliedTravelCredit;
                    $applied_referral->reservation_id = $reservation->id;
                    $applied_referral->referral_id = $row->id;
                    $applied_referral->amount = $coupon_amount;
                    $applied_referral->type = 'main';
                    $applied_referral->currency_code = $data['price_list']->currency;
                    $applied_referral->save();
                    $coupon_amount = 0;
                }
                }
            }
          }

        do
        {
            $code = $this->getCode(6, $reservation->id);
            $check_code = Reservation::where('code', $code)->get();
        }
        while(empty($check_code));

        $reservation_code = Reservation::find($reservation->id);

        $reservation_code->code = $code;

        $reservation_code->save();

        $days = $this->get_days($data['checkin'], $data['checkout']);
        
        // Update Calendar
        for($j=0; $j<count($days)-1; $j++)
        {
            $calendar_data = [
                            'room_id' => $data['room_id'],
                            'date'    => $days[$j],
                            'status'  => 'Not available'
                            ];

            Calendar::updateOrCreate(['room_id' => $data['room_id'], 'date' => $days[$j]], $calendar_data);
        }

        if($reservation_code->status == 'Accepted') 
        {
            $payouts = new Payouts;

            $payouts->reservation_id = $reservation_code->id;
            $payouts->room_id        = $reservation_code->room_id;
            $payouts->user_id        = $reservation_code->host_id;
            $payouts->user_type      = 'host';
            $payouts->amount         = $reservation_code->host_payout;
            $payouts->currency_code  = $reservation_code->currency_code;
            $payouts->status         = 'Future';
            
            $payouts->save();
        }

        $message = new Messages;

        $message->room_id        = $data['room_id'];
        $message->reservation_id = $reservation->id;
        $message->user_to        = $reservation->host_id;
        $message->user_from      = $reservation->user_id;
        $message->message        = @$data['message_to_host'];
        $message->message_type   = 1;
        $message->read           = 0;

        $message->save();

        $email_controller = new EmailController;
        $email_controller->booking($reservation->id);

        Session::forget('payment_room_id');
        Session::forget('payment_checkin');
        Session::forget('payment_checkout');
        Session::forget('payment_number_of_guests');
        Session::forget('payment_booking_type');
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');

        return $code;
    }

    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {    
        $sStartDate   = $this->payment_helper->date_convert($sStartDate);
        $sEndDate     = $this->payment_helper->date_convert($sEndDate);
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;  
       
        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;  
        }
      
        return $aDays;  
    }

    /**
     * Generate Reservation Code
     *
     * @param date $length  Code Length
     * @param date $seed    Reservation Id
     * @return string Reservation Code
     */
    public function getCode($length, $seed)
    {  
        $code = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";

        mt_srand($seed);

        for($i=0;$i<$length;$i++) {
            $code .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }

        return $code;
    }
}
