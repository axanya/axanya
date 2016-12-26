<?php

/**
 * Trips Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Trips
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Reservation;
use App\Models\Payouts;
use App\Models\Messages;
use App\Models\Calendar;
use App\Models\HostPenalty;
use App\Models\Rooms;
use App\Models\Fees;
use Auth;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use DateTime;

class TripsController extends Controller
{
    /**
     * Load Current Trips page.
     *
     * @return view Current Trips File
     */
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

       public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    public function current()
    {
        $data['pending_trips'] = Reservation::with('users', 'rooms')->where('status', '!=', 'contact')->where(function (
                $query
            ) {
                $query->where('status', 'Pending')->orwhere('status', 'Pre-Accepted');
            })->where('user_id', Auth::user()->user()->id)->get();

        $data['current_trips'] = Reservation::with('users','rooms')->where(function($query) {
                $query->where('checkin','>=',date('Y-m-d'))->where('checkout','<=',date('Y-m-d'));
            })->orWhere(function($query) {
                $query->where('checkin','<=',date('Y-m-d'))->where('checkout','>=',date('Y-m-d'));
        })
            ->where('status', '!=', 'Pending')
            ->where('status', '!=', 'Pre-Accepted')
            ->where('status', '!=', 'contact')
            ->where('user_id', Auth::user()->user()->id)
            ->get();

        $data['upcoming_trips'] = Reservation::with('users', 'rooms')
            ->where('checkin', '>', date('Y-m-d'))
            ->where('status', '!=', 'contact')
            ->where('status', '!=', 'Pre-Accepted')
            ->where('status', '!=', '')
            ->where('status', '!=', 'Pending')
            ->where('user_id', Auth::user()->user()->id)
            ->get();

        return view('trips.current', $data);
    }

    /**
     * Load Previous Trips page.
     *
     * @return view Previous Trips File
     */
    public function previous()
    {
        $data['previous_trips'] = Reservation::with('users','rooms')->where('checkout','<',date('Y-m-d'))->where('user_id',Auth::user()->user()->id)->get();

        return view('trips.previous', $data);
    }

    /**
     * Load Reservation Receipt file.
     *
     * @return view Receipt
     */
    public function receipt(Request $request)
    {
        $data['reservation_details'] = Reservation::where('code',$request->code)->first();

        if($data['reservation_details']->user_id != Auth::user()->user()->id)
            abort('404');

        $data['additional_title'] = $request->code;

        return view('trips.receipt', $data);
    }


    /**
     * Reservation Cancel by Guest
     *
     * @param array $request Input values
     *
     * @return redirect to Current Trips page
     */
    public function guest_cancel_pending_reservation(Request $request)
    {
        $reservation_details = Reservation::find($request->id);
        if ($reservation_details->status == 'Cancelled' || $reservation_details->status == 'Declined' || $reservation_details->status == 'Expired')
        {
            return redirect('trips/current');
        }
        $rooms_details = Rooms::find($reservation_details->room_id);
        $payouts       = new Payouts;

        $payouts->reservation_id = $request->id;
        $payouts->room_id        = $reservation_details->room_id;
        $payouts->user_id        = $reservation_details->user_id;
        $payouts->user_type      = 'guest';
        $payouts->amount         = $reservation_details->total;
        $payouts->currency_code  = $reservation_details->currency_code;
        $payouts->created_at     = date('Y-m-d H:m:s');
        $payouts->status         = 'Future';

        $payouts->save();

        $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);

// Update Calendar, delete booked dates

        for ($j = 0; $j < count($days) - 1; $j++)
        {

            Calendar::where('room_id', $reservation_details->room_id)
                ->where('date', $days[$j])
                ->where('status', 'Not available')
                ->delete();

        }

// Update Calendar,delete booked dates
        $messages                 = new Messages;
        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->host_id;
        $messages->user_from      = Auth::user()->user()->id;
        $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
        $messages->message_type   = 10;

        $messages->save();

        $cancel = Reservation::find($request->id);

        $cancel->cancelled_by     = "Guest";
        $cancel->cancelled_reason = $request->cancel_reason;
        $cancel->cancelled_at     = date('Y-m-d H:m:s');
        $cancel->status           = "Cancelled";

        $cancel->save();

        $this->helper->flash_message('success', 'Reservation Successfully Cancelled');

        return redirect('trips/current');
    }

    /**
     * Reservation Cancel by Guest
     *
     * @param array $request Input values
     * @return redirect to Current Trips page
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

    public function guest_cancel_reservation(Request $request)
    {

      $reservation_details = Reservation::find($request->id);
        if ($reservation_details->status == 'Cancelled') return redirect('trips/current');
    
      $rooms_details = Rooms::find($reservation_details->room_id);
// penalty refund process

    $penalty_revert = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id',$request->id)->get();
    
    
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
       
// penalty refund process end


        $datetime1 = new DateTime(); 
        $datetime2 = new DateTime($reservation_details->checkin); 
        $interval_diff = $datetime1->diff($datetime2);
        
        $interval = $interval_diff->days;
        // host fee calculation

        $host_fee_percentage              = Fees::find(2)->value;

        // host fee calcualtion

    if($datetime1 < $datetime2 )
    { 
            
    // Check cancellation policy for Flexible

        if($rooms_details->cancel_policy == "Flexible")
        {
           
/* Check the current date if its lessthan the checkin date */

            if ($interval == 0)
            {
                 
            // first night is non-refundable

                $refundable_price = ($reservation_details->subtotal - $reservation_details->per_night - $reservation_details->cleaning - $reservation_details->security);
                //dd($reservation_details->cleaning);
                  $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $refundable_price;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

            
// Deduct penalty amount start

                $penalty = HostPenalty::where('user_id',$reservation_details->host_id)->where('remain_amount','!=',0)->get();

                if ($host_fee_percentage > 0)
                {
                    $host_first_night_price = ($reservation_details->per_night - ($reservation_details->per_night * ($host_fee_percentage / 100)));
                }
                else
                {
                    $host_first_night_price = $reservation_details->per_night;
                }

                $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_first_night_price,
                    $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end

                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->get();
          

                $payouts                  = Payouts::find($payouts_id[0]->id);

                $payouts->amount          = $host_amount;

                $payouts->penalty_id      = $penalty_id;

                $payouts->penalty_amount  = $penalty_amount;

                $payouts->save();


            }
            elseif ($interval > 0)
            {
               // if cancel date greaterthan one, the total amount will refund to guest 
               
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount          = $reservation_details->subtotal;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

                $payouts_host_amount     = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->delete();
    
            }
        }

    // Check cancellation policy for Moderate

        if($rooms_details->cancel_policy == "Moderate")
        {
           
/* Check the current date if its lessthan the checkin date */

            if ($interval < 5)
            {
                 
            // first night is non-refundable
              
                $refundable_price = ($reservation_details->subtotal - $reservation_details->per_night);

            // As per Moderate policy 50% maount will be refunded to host

                $guest_refundable_price = (50 / 100) * $refundable_price;

                $host_refundable_price = $reservation_details->per_night + $guest_refundable_price;

                  $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $guest_refundable_price;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

            
// Deduct penalty amount start

                $penalty = HostPenalty::where('user_id',$reservation_details->host_id)->where('remain_amount','!=',0)->get();

                if ($host_fee_percentage > 0)
                {
                    $host_first_night_price = ($host_refundable_price - ($host_refundable_price * ($host_fee_percentage / 100)));
                }
                else
                {
                    $host_first_night_price = $host_refundable_price;
                }

                $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_first_night_price,
                    $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end


                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->get();
          

                $payouts                  = Payouts::find($payouts_id[0]->id);

                $payouts->amount          = $host_amount;

                $payouts->penalty_id      = $penalty_id;

                $payouts->penalty_amount  = $penalty_amount;

                $payouts->save();


            }
            elseif ($interval >= 5)
            {
               // if cancel date greaterthan five, the total amount will refund to guest 
               
                $payouts = new Payouts;
    
                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount          = $reservation_details->subtotal;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';
    
                $payouts->save();

                $payouts_host_amount     = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->delete();
    
            }
        }

// Check cancellation policy for Strict

        if ($rooms_details->cancel_policy == "Strict")
        {

            /* Check the current date if its lessthan the checkin date */

            if ($interval < 7)
            {

                // first night is non-refundable

                $refundable_price = $reservation_details->subtotal;

                // If the guest cancels less than 7 days in advance, the nights not spent are not refunded.

                $guest_refundable_price = $reservation_details->cleaning + $reservation_details->security;

                $host_refundable_price = $reservation_details->subtotal;

                if ($guest_refundable_price > 0)
                {
                    $payouts = new Payouts;

                    $payouts->reservation_id = $request->id;
                    $payouts->room_id        = $reservation_details->room_id;
                    $payouts->user_id        = $reservation_details->user_id;
                    $payouts->user_type      = 'guest';
                    $payouts->amount         = $guest_refundable_price;
                    $payouts->currency_code  = $reservation_details->currency_code;
                    $payouts->status         = 'Future';

                    $payouts->save();
                }

// Deduct penalty amount start

                $penalty = HostPenalty::where('user_id', $reservation_details->host_id)
                    ->where('remain_amount', '!=', 0)
                    ->get();

                if ($host_fee_percentage > 0)
                {
                    $host_refund_night_price = ($host_refundable_price - ($host_refundable_price * ($host_fee_percentage / 100)));
                }
                else
                {
                    $host_refund_night_price = $host_refundable_price;
                }

                $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_refund_night_price,
                    $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end

                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)
                    ->where('reservation_id', $request->id)
                    ->get();

                $payouts = Payouts::find($payouts_id[0]->id);

                $payouts->amount = $host_amount;

                $payouts->penalty_id = $penalty_id;

                $payouts->penalty_amount = $penalty_amount;

                $payouts->save();


            }
            elseif ($interval >= 7)
            {
                // if cancel date greaterthan seven, the total 50 % amount will refund to guest

                $strict_refund = $reservation_details->cleaning + $reservation_details->security;

                $guest_refund_amount = ((50 / 100) * ($reservation_details->subtotal - $strict_refund)) + $strict_refund;

                $host_refund_price = $reservation_details->subtotal - $guest_refund_amount;

                $payouts = new Payouts;

                $payouts->reservation_id = $request->id;
                $payouts->room_id        = $reservation_details->room_id;
                $payouts->user_id        = $reservation_details->user_id;
                $payouts->user_type      = 'guest';
                $payouts->amount         = $guest_refund_amount;
                $payouts->currency_code  = $reservation_details->currency_code;
                $payouts->status         = 'Future';

                $payouts->save();

// Deduct penalty amount start

                $penalty = HostPenalty::where('user_id', $reservation_details->host_id)
                    ->where('remain_amount', '!=', 0)
                    ->get();

                if ($host_fee_percentage > 0)
                {
                    $host_refund_night_price = ($host_refund_price - ($host_refund_price * ($host_fee_percentage / 100)));
                }
                else
                {
                    $host_refund_night_price = $host_refund_price;
                }

                $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_refund_night_price,
                    $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end

                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)
                    ->where('reservation_id', $request->id)
                    ->get();

                $payouts = Payouts::find($payouts_id[0]->id);

                $payouts->amount = $host_amount;

                $payouts->penalty_id = $penalty_id;

                $payouts->penalty_amount = $penalty_amount;

                $payouts->save();


            }
        }



            $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
        
// Update Calendar, delete booked dates

        for($j=0; $j<count($days)-1; $j++)
            {
           
            Calendar::where('room_id', $reservation_details->room_id)->where('date', $days[$j])->where('status', 'Not available')->delete();

            }

// Update Calendar,delete booked dates
            
/* Check the current date  if its lessthan the checkin date end*/  
    }
    else
        {

                      /*--- cancel in checkin date ---*/

                 if($interval == 0)
            {
                 
                // first night is non-refundable
                
                if($rooms_details->cancel_policy == "Moderate")
                    {

                    $guest_refund = ($reservation_details->subtotal - $reservation_details->per_night);

                    $refundable_price = (50 / 100) * $guest_refund;

                    $host_refund = $reservation_details->per_night + $refundable_price;

                    }

                if($rooms_details->cancel_policy == "Flexible")
                {

                    $refundable_price = (($reservation_details->subtotal - $reservation_details->per_night) - $reservation_details->cleaning);

                    $host_refund      = $reservation_details->per_night;

                }

                     if ($rooms_details->cancel_policy == "Strict")
                     {

                         $refundable_price = $reservation_details->security;

                         $host_refund = (($reservation_details->total - $reservation_details->service) - $reservation_details->security);

                     }

                     if ($refundable_price > 0)
                     {
                         $payouts = new Payouts;

                         $payouts->reservation_id = $request->id;
                         $payouts->room_id        = $reservation_details->room_id;
                         $payouts->user_id        = $reservation_details->user_id;
                         $payouts->user_type      = 'guest';
                         $payouts->amount         = $refundable_price;
                         $payouts->currency_code  = $reservation_details->currency_code;
                         $payouts->status         = 'Future';

                         $payouts->save();
                     }


// Deduct penalty amount start

                     $penalty = HostPenalty::where('user_id', $reservation_details->host_id)
                         ->where('remain_amount', '!=', 0)
                         ->get();

                     if ($host_fee_percentage > 0)
                     {
                         $host_night_price = ($host_refund - ($host_refund * ($host_fee_percentage / 100)));
                     }
                     else
                     {
                         $host_night_price = $host_refund;
                     }

                     $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_night_price,
                         $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end

// update host payout   
                
                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->get();
          

                $payouts = Payouts::find($payouts_id[0]->id);
                $payouts->amount          =  $host_amount;
                $payouts->penalty_id      = $penalty_id;
                $payouts->penalty_amount  = $penalty_amount;
                $payouts->save();

// update host payout      
        
// Update Calendar, delete booked dates

                $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);

                for($j=0; $j<count($days)-1; $j++)
                {
                Calendar::where('room_id',$reservation_details->room_id)->where('date', $days[$j])->where('status', 'Not available')->delete();
                }

// Update Calendar, delete booked dates

            }
/*--- cancel in checkin date ---*/
            else
            {
               
/*--Cancel after checkin date ---*/  

// calculate the not stay nights, guest amount 

                $remaining_nights =  $reservation_details->nights - $interval;

                if($rooms_details->cancel_policy == "Moderate")
                {

                    $refundable_to_guest = ($remaining_nights * $reservation_details->per_night);

                    $refundable_price_guest = ((50 / 100) * $refundable_to_guest) + $reservation_details->security;

                    $refundable_price_host  = ($reservation_details->subtotal - $refundable_price_guest);

                    }

                if($rooms_details->cancel_policy == "Flexible")
                    {

                    $refundable_price_guest = ($remaining_nights * $reservation_details->per_night) + $reservation_details->security;

                    $refundable_price_host  = ($reservation_details->subtotal - $refundable_price_guest);

                    }

                if ($rooms_details->cancel_policy == "Strict")
                {

                    $refundable_price_guest = $reservation_details->security;;

                    $refundable_price_host = (($reservation_details->total - $reservation_details->service) - $reservation_details->security);

                }

                if ($refundable_price_guest > 0)
                {
                    $payouts = new Payouts;

                    $payouts->reservation_id = $request->id;
                    $payouts->room_id        = $reservation_details->room_id;
                    $payouts->user_id        = $reservation_details->user_id;
                    $payouts->user_type      = 'guest';
                    $payouts->amount         = $refundable_price_guest;
                    $payouts->currency_code  = $reservation_details->currency_code;
                    $payouts->status         = 'Future';
                    $payouts->penalty_amount = 0;
                    $payouts->penalty_id     = 0;

                    $payouts->save();
                }
// calculate the not stay nights, guest amount 
                
// Deduct penalty amount start

                $penalty = HostPenalty::where('user_id', $reservation_details->host_id)
                    ->where('remain_amount', '!=', 0)
                    ->get();

                if ($host_fee_percentage > 0)
                {
                    $host_refund_night_price = ($refundable_price_host - ($refundable_price_host * ($host_fee_percentage / 100)));
                }
                else
                {
                    $host_refund_night_price = $refundable_price_host;
                }

                $penalty_result = $this->payment_helper->check_host_penalty($penalty, $host_refund_night_price,
                    $reservation_details->currency_code);

                $host_amount    = $penalty_result['host_amount'];
                $penalty_id     = $penalty_result['penalty_id'];
                $penalty_amount = $penalty_result['penalty_amount'];

// Deduct penalty amount end


                $payouts_id = Payouts::where('user_id', $reservation_details->host_id)->where('reservation_id', $request->id)->get();

                $payouts = Payouts::find($payouts_id[0]->id);

                $payouts->amount   = $host_amount;

                $payouts->penalty_id      = $penalty_id;

                $payouts->penalty_amount  = $penalty_amount;

                $payouts->save();


                $cancelled_date = date('Y-m-d H:m:s');
                $days = $this->get_days($cancelled_date ,$reservation_details->checkout);
       
// Update Calendar, delete stayed date
                for($j=0; $j<count($days)-1; $j++)
                  {

                    Calendar::where('room_id', $reservation_details->room_id)->where('date', $days[$j])->where('status', 'Not available')->delete();
                  }
// Update Calendar, delete stayed date

/*--Cancel after checkin date ---*/ 
              }
          }
      
                $messages = new Messages;
    
                $messages->room_id        = $reservation_details->room_id;
                $messages->reservation_id = $reservation_details->id;
                $messages->user_to        = $reservation_details->host_id;
                $messages->user_from      = Auth::user()->user()->id;
        $messages->message                = $this->helper->phone_email_remove($request->cancel_message);
                $messages->message_type   = 10;
    
                $messages->save();
    
    
                $cancel = Reservation::find($request->id);
    
                $cancel->cancelled_by = "Guest";
                $cancel->cancelled_reason = $request->cancel_reason;
                $cancel->cancelled_at = date('Y-m-d H:m:s');
                $cancel->status = "Cancelled";
    
                $cancel->save();
       
       $this->helper->flash_message('success', 'Reservation Successfully Cancelled');

        return redirect('trips/current');
        
    }

}
