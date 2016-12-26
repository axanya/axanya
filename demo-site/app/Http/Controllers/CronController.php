<?php

/**
 * Cron Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Cron
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IcalController;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Auth;
use App\Models\Currency;
use App\Models\ImportedIcal;
use App\Models\Calendar;
use App\Models\Reservation;
use App\Models\Payouts;
use App\Models\Messages;
use App\Models\Fees;
use App\Models\HostPenalty;
use App\Models\Referrals;
use DateTime;
use Swap;
use DB;
use Session;

class CronController extends Controller
{
    /**
     * Update currency rate based on Swap Config file
     *
     * @param array $swap   Instance of SwapInterface
     * @return redirect     to Home page
     */
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

    public function currency()
    {
        // Get all currencies from Currency table
        $result = Currency::all();

        // Update Currency rate by using Code as where condition
        foreach($result as $row)
        {
            if($row->code != 'USD')
                $rate = Swap::quote('USD/'.$row->code);
            else
                $rate = 1;

            Currency::where('code',$row->code)->update(['rate' => $rate]);
        }
    }

    /**
     * iCal Synchronization for all Imported iCal URLs
     *
     * @return redirect     to Home page
     */
    public function ical_sync()
    {
        // Get all imported iCal URLs
        $result = ImportedIcal::all();

        foreach($result as $row)
        {
            // Create a new instance of IcalController
            $ical = new IcalController($row->url);
            $events= $ical->events();

            // Get events from IcalController
            for($i=0; $i<$ical->event_count; $i++)
            {
                $start_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);

                $end_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTEND']);

                $days = $this->get_days($start_date, $end_date);

                // Update or Create a events
                for($j=0; $j<count($days)-1; $j++)
                {
                    $calendar_data = [
                                'room_id' => $row->room_id,
                                'date'    => $days[$j],
                                'notes'   => @$events[$i]['DESCRIPTION'],
                                'status'  => 'Not available'
                                ];

                    Calendar::updateOrCreate(['room_id' => $row->room_id, 'date' => $days[$j]], $calendar_data);
                }
            }

            // Update last synchronization DateTime
            $imported_ical = ImportedIcal::find($row->id);

            $imported_ical->last_sync = date('Y-m-d H:i:s');

            $imported_ical->save();
        }
    }


    /**
     * Get dates between two dates
     *
     * @param date $sStartDate Start Date
     * @param date $sEndDate   End Date
     *
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate, $format = 'dmy')
    {
        if ($format == 'dmy')
        {
            $sStartDate = gmdate("Y-m-d", $sStartDate);
            $sEndDate   = gmdate("Y-m-d", $sEndDate);
        }

        $aDays[] = $sStartDate;

        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

            $aDays[] = $sCurrentDate;
        }

        return $aDays;
    }


    /**
     * Update Expired Reservations
     *
     * @return redirect     to Home page
     */
    public function expire()
    {
        $reservation_all = Reservation::where('status', 'Pending')->get();

        foreach($reservation_all as $row)
        {
            $reservation_details = Reservation::find($row->id);

            // Expire penalty
            $cancel_count = Reservation::where('host_id', $reservation_details->host_id)
                ->where('cancelled_by', 'Host')
                ->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))
                ->get()
                ->count();
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
            $code = DB::table('currency')->where('default_currency', 1)->value('code');

            $to_time                        = strtotime($reservation_details->created_at);
            $from_time                      = strtotime(date('Y-m-d H:i:s'));
            $diff_mins                      = round(abs($to_time - $from_time) / 60, 2);

            if ($diff_mins >= 1440)
            {
                // Expire penalty
                if ($host_penalty != 0 && $cancel_count >= $penalty_cancel_limits_count)
                {
                $penalty = new HostPenalty;

                $penalty->room_id = $reservation_details->room_id;
                $penalty->user_id = $reservation_details->host_id;
                $penalty->reservation_id = $row->id;
                $penalty->currency_code  = $reservation_details->currency_code;
                $penalty->amount = $this->payment_helper->currency_convert($penalty_currency,$code,$penalty_before_days);
                    $penalty->remain_amount = $penalty->amount;
                $penalty->status  = "Pending";
                $penalty->save();
                }
          // Expire penalty


                $this->payment_helper->revert_travel_credit($row->id);

                $reservation_details->status       = 'Expired';
                $reservation_details->expired_at   = date('Y-m-d H:m:s');

                $reservation_details->save();

                $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout, 'ymd');

                // Update Calendar
                for($j=0; $j<count($days)-1; $j++)
                {
                    Calendar::where('room_id', $reservation_details->room_id)->where('date', $days[$j])->where('status', 'Not available')->delete();
                }

                $payouts = new Payouts;

                $payouts->reservation_id = $row->id;
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
                $messages->user_from      = $reservation_details->host_id;
                $messages->message        = '';
                $messages->message_type   = 4;

                $messages->save();
            }
        }
    }


    /**
     * Update Travel Credit After Checkin
     *
     * @return redirect     to Home page
     */
    public function travel_credit()
    {
        $reservation_all = Reservation::where('status', '=', 'Accepted')->get();

      foreach($reservation_all as $row)
      {
        if($row->checkin_cross == 0)
        {
          $guest_referral = Referrals::whereFriendId($row->user_id)->where('if_friend_guest_amount', '!=', 0)->first();
            $guest_amount = @$guest_referral->if_friend_guest_amount_original;
          $prev_credited_amount = @$guest_referral->credited_amount;


          if(@$guest_referral->id) {
            $referral = Referrals::find($guest_referral->id);
            $referral->credited_amount = $prev_credited_amount + $guest_amount;
            $referral->if_friend_guest_amount = 0;
            $referral->save();
          }

          $host_referral = Referrals::whereFriendId($row->host_id)->where('if_friend_host_amount', '!=', 0)->first();
            $host_amount        = @$host_referral->if_friend_host_amount_original;
          $prev_credited_amount = @$host_referral->credited_amount;

          if(@$host_referral->id) {
            $referral = Referrals::find($host_referral->id);
            $referral->credited_amount = $prev_credited_amount + $host_amount;
            $referral->if_friend_host_amount = 0;
            $referral->save();
          }

          Referrals::whereIfFriendGuestAmount(0)->whereIfFriendHostAmount(0)->update(['status'=>'Completed']);
        }
      }
    }


    public function review_remainder(EmailController $email)
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $result = Reservation::where('status', 'Accepted')->where('checkout', $yesterday)->get();

        foreach ($result as $row)
        {
            $reservation = Reservation::find($row->id);
            $email->review_remainder($reservation, 'guest');
            $email->review_remainder($reservation, 'host');
        }
    }
}
