<?php

/**
 * Reservation Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     0.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Reservation;
use App\Models\Messages;
use App\Models\Calendar;
use App\Models\Rooms;
use App\Models\RoomsPhotos;
use App\Models\ReservationAlteration;
use App\Models\HostPenalty;
use App\Models\Payouts;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Fees;
use DateTime;
use DB;
use Session;

class ReservationController extends Controller
{
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['reservation_id'] = $request->id;

        $read_count   = Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->where('read',0)->count();

        if($read_count !=0)
        {
        Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->user()->id)->update(['read' =>'1']);  
        }

        $data['result']         = Reservation::find($request->id);

        if(!$data['result'])
            abort('404');

        if($data['result']->host_id != Auth::user()->user()->id)
            abort('404');

        return view('reservation.reservation_detail', $data);
    }

    /**
     * Reservation Request Accept by Host
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function accept(Request $request)
    {        
       
        $reservation_details = Reservation::find($request->id);
        
        // penalty amount 

    $penalty = HostPenalty::where('user_id',Auth::user()->user()->id)->where('remain_amount','!=',0)->get();

     $penalty_result = $this->payment_helper->check_host_penalty($penalty,$reservation_details->host_payout,$reservation_details->currency_code);
      

      $host_amount    = $penalty_result['host_amount'];
      $penalty_id     = $penalty_result['penalty_id'];
      $penalty_amount = $penalty_result['penalty_amount'];

        // penalty amount 
            
        $reservation_details->status            = 'Accepted';
        $reservation_details->accepted_at       = date('Y-m-d H:m:s');

        $reservation_details->save();


        $payouts = new Payouts;

        $payouts->reservation_id = $request->id;
        $payouts->room_id        = $reservation_details->room_id;
        $payouts->user_id        = $reservation_details->host_id;
        $payouts->user_type      = 'host';
        $payouts->amount         = $host_amount;
        $payouts->penalty_amount = $penalty_amount;
        $payouts->penalty_id     = $penalty_id;
        $payouts->currency_code  = $reservation_details->currency_code;
        $payouts->status         = 'Future';

        $payouts->save();

        $messages = new Messages;

        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->user_id;
        $messages->user_from      = Auth::user()->user()->id;
        $messages->message        = $request->message;
        $messages->message_type   = 2;

        $messages->save();

        $this->helper->flash_message('success', 'Reservation Request has Successfully Accepted'); // Call flash message function
        return redirect('reservation/'.$request->id);
    }

    /**
     * Reservation Request Decline by Host
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function decline(Request $request)
    {
        $reservation_details = Reservation::find($request->id);

        $reservation_details->status          = 'Declined';
        $reservation_details->decline_reason  = ($request->decline_reason == 'other') ? $request->decline_reason_other : $request->decline_reason;
        $reservation_details->declined_at     = date('Y-m-d H:m:s');

        $reservation_details->save();

        $payouts = new Payouts;

        $payouts->reservation_id = $request->id;
        $payouts->room_id        = $reservation_details->room_id;
        $payouts->user_id        = $reservation_details->user_id;
        $payouts->user_type      = 'guest';
        $payouts->amount         = $reservation_details->guest_payout;
        $payouts->currency_code  = $reservation_details->currency_code;
        $payouts->status         = 'Future';

        $payouts->save();

        if($request->block_calendar == 'yes')
        {
            $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
        
            // Update Calendar
            for($j=0; $j<count($days)-1; $j++)
            {
                $calendar_data = [
                                'room_id' => $reservation_details->room_id,
                                'date'    => $days[$j],
                                'status'  => 'Not available'
                                ];

                Calendar::updateOrCreate(['room_id' => $reservation_details->room_id, 'date' => $days[$j]], $calendar_data);
            }
        }
        else
        {
            $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
        
            // Update Calendar
            for($j=0; $j<count($days)-1; $j++)
            {
                $calendar_data = [
                                'room_id' => $reservation_details->room_id,
                                'date'    => $days[$j],
                                'status'  => 'Available'
                                ];

                Calendar::updateOrCreate(['room_id' => $reservation_details->room_id, 'date' => $days[$j]], $calendar_data);
            }
        }

        $messages = new Messages;

        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->user_id;
        $messages->user_from      = Auth::user()->user()->id;
        $messages->message        = $request->message;
        $messages->message_type   = 3;

        $messages->save();

        $this->payment_helper->revert_travel_credit($request->id);

        $this->helper->flash_message('success', 'Reservation Request has Successfully Declined'); // Call flash message function
        return redirect('reservation/'.$request->id);
    }

    /**
     * Reservation Request Expire
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function expire(Request $request)
    {
        $reservation_details = Reservation::find($request->id);


        // Expire penalty
        $cancel_count = Reservation::where('host_id', Auth::user()->user()->id)->where('cancelled_by', 'Host')->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))->get()->count();
          // penalty management admin panel

          $host_penalty        = Fees::find(3)->value;

          $penalty_currency    = Fees::find(4)->value;

          $penalty_before_days = Fees::find(5)->value;

          $penalty_after_days  = Fees::find(6)->value;

          $penalty_cancel_limits_count  = Fees::find(7)->value;

        // penalty management admin panel
          
          if(Session::get('currency'))
            $code =  Session::get('currency');
          else
            $code = DB::table('currency')->where('default_currency', 1)->first()->code;

          if($host_penalty != 0 && $cancel_count > $penalty_cancel_limits_count)
          {
                $penalty = new HostPenalty;
    
                $penalty->room_id = $reservation_details->room_id;
                $penalty->user_id = $reservation_details->host_id;
                $penalty->reservation_id = $request->id;
                $penalty->currency_code  = $reservation_details->currency_code;
                $penalty->amount = $this->payment_helper->currency_convert($penalty_currency,$code,$penalty_before_days);
                $penalty->remain_amount = $penalty->amount;      
                $penalty->status  = "Pending";
                $penalty->save();
          }
          // Expire penalty

        $to_time   = strtotime($reservation_details->created_at);
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff_mins = round(abs($to_time - $from_time) / 60,2);

        if($diff_mins >= 1440)
        {
            $reservation_details->status       = 'Expired';
            $reservation_details->expired_at   = date('Y-m-d H:m:s');

            $reservation_details->save();

            $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
        
            // Update Calendar
            for($j=0; $j<count($days)-1; $j++)
            {
                /*$calendar_data = [
                                'room_id' => $reservation_details->room_id,
                                'date'    => $days[$j],
                                'status'  => 'Available'
                                ];

                Calendar::updateOrCreate(['room_id' => $reservation_details->room_id, 'date' => $days[$j]], $calendar_data);*/
                Calendar::where('room_id', $reservation_details->room_id)->where('date', $days[$j])->where('status', 'Not available')->delete();
            }

            $payouts = new Payouts;

            $payouts->reservation_id = $request->id;
            $payouts->room_id        = $reservation_details->room_id;
            $payouts->user_id        = $reservation_details->user_id;
            $payouts->user_type      = 'guest';
            $payouts->amount         = $reservation_details->guest_payout;
            $payouts->currency_code  = $reservation_details->currency_code;
            $payouts->status         = 'Future';

            $payouts->save();

            $messages = new Messages;

            $messages->room_id        = $reservation_details->room_id;
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->user_id;
            $messages->user_from      = Auth::user()->user()->id;
            $messages->message        = '';
            $messages->message_type   = 4;

            $messages->save();

            $this->payment_helper->revert_travel_credit($request->id);

            $this->helper->flash_message('success', 'Reservation Request has Expired'); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
        else
        {
            $this->helper->flash_message('error', 'Still your reservation has time to expire'); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
    }

    /**
     * Show Host Reservations
     *
     * @param array $request Input values
     * @return redirect to My Reservations page
     */
    public function my_reservations(Request $request)
    {
        if($request->all == 1)
        {
            $data['code'] = '1';
            $data['reservations'] = Reservation::where('host_id', Auth::user()->user()->id)->where('status','!=','contact')->get();
        }
        else
        {
            $data['code'] = '0';
            $data['reservations'] = Reservation::where('host_id', Auth::user()->user()->id)->where('checkout','>=',date('Y-m-d'))->where('status','!=','contact')->get();
        }

        $data['print'] = $request->print;

        return view('reservation.my_reservations', $data);
    }

    /**
     * Load Reservation Itinerary Print Page
     *
     * @param array $request Input values
     * @return view Itinerary file
     */
    public function print_confirmation(Request $request)
    {
        $data['reservation_details'] = Reservation::with('rooms','users')->where('code',$request->code)->first();

        $data['additional_title'] = $request->code;

        if($data['reservation_details'])
        {
            if($data['reservation_details']->host_id == Auth::user()->user()->id)
                return view('reservation.print_confirmation', $data);
            else if($data['reservation_details']->user_id == Auth::user()->user()->id)
                return view('trips.itinerary', $data);
            else
                abort('404');
        }
        else
            abort('404');
    }

    /**
     * Load Reservation Requested Page for After Payment
     *
     * @param array $request Input values
     * @return view Reservation Requested file
     */
    public function requested(Request $request)
    {
        $data['reservation_details'] = Reservation::where('code', $request->code)->first();

        return view('reservation.requested', $data);
    }

    /**
     * Store Itinerary Friends
     *
     * @param array $request Input values
     * @return redirect to Trips page
     */
    public function itinerary_friends(Request $request)
    {
        $friends_email = '';

        for($i=0; $i<count($request->friend_address); $i++)
        {
            if($request->friend_address[$i] != '')
            {
                $friends_email .= trim($request->friend_address[$i]).',';
            }
        }

        $reservation = Reservation::where('code',$request->code)->update(['friends_email'=>rtrim($friends_email,',')]);

        return redirect('trips/current'); 
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
     * Reservation Cancel by Host
     *
     * @param array $request Input values
     * @return redirect to My Reservations page
     */
    public function host_cancel_reservation(Request $request)
    {

    $reservation_details = Reservation::find($request->id);

    if($reservation_details->checkin_cross == 1)
      $this->payment_helper->revert_travel_credit($request->id);

    // print_r($reservation_details->subtotal);exit;
    // penalry refund process

    $penalty_revert = Payouts::where('user_id', Auth::user()->user()->id)->where('reservation_id',$request->id)->get();
    
    
      if($penalty_revert[0]->penalty_id != 0 && $penalty_revert[0]->penalty_id != '')
      {

      $penalty_id = explode(",",$penalty_revert[0]->penalty_id);

      $penalty_amt = explode(",",$penalty_revert[0]->penalty_amount);

        $i =0;
        foreach ($penalty_id as $row) 
        {


          $old_amt = HostPenalty::where('id',$row)->get();

          $upated_amt = $old_amt[0]->remain_amount + $penalty_amt[$i];

          HostPenalty::where('id',$row)->update(['remain_amount' => $upated_amt,'status' => 'Pending' ]); 
      
          $i++;

        }
      }
       
    // penalry refund process

        $datetime1 = new DateTime(); 
        $datetime2 = new DateTime($reservation_details->checkin); 
        $interval_diff = $datetime1->diff($datetime2);

        $interval = $interval_diff->days;

        $penalty = HostPenalty::where('user_id', Auth::user()->user()->id)->where('reservation_id',$request->id)->get();

        $cancel_count = Reservation::where('host_id', Auth::user()->user()->id)->where('cancelled_by', 'Host')->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))->get()->count();

        // penalty management admin panel

          $host_penalty        = Fees::find(3)->value;

          $penalty_currency    = Fees::find(4)->value;

          $penalty_before_days = Fees::find(5)->value;

          $penalty_after_days  = Fees::find(6)->value;

          $penalty_cancel_limits_count  = Fees::find(7)->value;

        // penalty management admin panel
          
          if(Session::get('currency'))
            $code =  Session::get('currency');
          else
            $code = DB::table('currency')->where('default_currency', 1)->first()->code;

        if($datetime1 < $datetime2 )
        {           
           
            if( $host_penalty == 1)
            {
                if($cancel_count <= $penalty_cancel_limits_count)
                 {  
                     
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $reservation_details->guest_payout;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

                 }
                 else
                 {
                  
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $reservation_details->guest_payout;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

                $penalty = new HostPenalty;
    
                $penalty->room_id = $reservation_details->room_id;
                $penalty->user_id = $reservation_details->host_id;
                $penalty->reservation_id = $request->id;
                $penalty->currency_code  = $reservation_details->currency_code;


                if($interval > 7)
                    {

                $penalty->amount = $this->payment_helper->currency_convert($penalty_currency,$code,$penalty_before_days);

                $penalty->remain_amount = $penalty->amount;
                
                    }
                else
                    {

                $penalty->amount = $this->payment_helper->currency_convert($penalty_currency,$code,$penalty_after_days);

                $penalty->remain_amount = $penalty->amount;

                    }

                $penalty->status  = "Pending";

                $penalty->save();


                 }
            }
            else
            {
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $reservation_details->guest_payout;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();
             
            }

            $payouts_host_amount     = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->delete();
            
        }
        else
        {   
               

            $remaining_nights =  $reservation_details->nights - $interval;

            $refundable_price_guest = ($remaining_nights * $reservation_details->per_night) + $reservation_details->security;

            $refundable_price_host  = ($reservation_details->subtotal - $refundable_price_guest);

            // penalty amount 

            $penalty = HostPenalty::where('user_id',Auth::user()->user()->id)->where('remain_amount','!=',0)->get();

            $penalty_result = $this->payment_helper->check_host_penalty($penalty,$refundable_price_host,$reservation_details->currency_code);
         
            $host_amount    = $penalty_result['host_amount'];
            $host_penalty_id     = $penalty_result['penalty_id'];
            $host_penalty_amount = $penalty_result['penalty_amount'];
      
            // penalty amount 

            
            if($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1)
            { 

             $penalty_amount = $this->payment_helper->currency_convert($penalty_currency,$code,$penalty_after_days);
             
            }
            else
            {

             $penalty_amount = 0;   

            }
          
            if($host_amount > $penalty_amount )
            {
            $host_amount           = ($host_amount - $penalty_amount);
            $get_penalty           = $penalty_amount ;
            $remaining_penalty     = 0;
            
            }
            else
            {
               
            $get_penalty = ($penalty_amount - $host_amount);
            $remaining_penalty     = ($penalty_amount - $get_penalty );
            $host_amount = 0; 
            }    
           
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $refundable_price_guest;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
                $payouts->penalty_amount = $host_penalty_amount;
                $payouts->penalty_id     = $host_penalty_id;
    
                $payouts->save();

                if($cancel_count >= $penalty_cancel_limits_count)
                 { 

                $penalty = new HostPenalty;
    
                $penalty->reservation_id = $request->id;
                $penalty->room_id        = $reservation_details->room_id;
                $penalty->user_id        = $reservation_details->host_id;
                $penalty->remain_amount  = $remaining_penalty;
                $penalty->amount         = $penalty_amount;
                $penalty->currency_code  = $reservation_details->currency_code;
                if($remaining_penalty != 0)
                $penalty->status         = 'Pending';
                else
                $penalty->status         = 'Completed';  
                $penalty->save();

                 }

                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->get();


                $payouts = Payouts::find($payouts_id[0]->id);

                $payouts->amount         = $host_amount;
                if($payouts->penalty_amount !=0)
                {
                $payouts->penalty_amount = $payouts->penalty_amount.','.$get_penalty;
                $payouts->penalty_id     = $payouts->penalty_id.','.@$penalty->id;
                }
                else
                {
                $payouts->penalty_amount = $get_penalty;
                $payouts->penalty_id     = @$penalty->id;
                }

                $payouts->save();

        }
            
                $messages = new Messages;
    
                $messages->room_id        = $reservation_details->room_id;
                $messages->reservation_id = $reservation_details->id;
                $messages->user_to        = $reservation_details->user_id;
                $messages->user_from      = Auth::user()->user()->id;
                $messages->message        = $request->cancel_message;
                $messages->message_type   = 11;
    
                $messages->save();
    
    
                $cancel = Reservation::find($request->id);
    
                $cancel->cancelled_by = "Host";
                $cancel->cancelled_reason = $request->cancel_reason;
                $cancel->cancelled_at = date('Y-m-d H:m:s');
                $cancel->status = "Cancelled";
    
                $cancel->save();
       
       $this->helper->flash_message('success', 'Reservation Successfully Cancelled');
        return redirect('my_reservations');
    }


}
