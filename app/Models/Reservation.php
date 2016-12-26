<?php

/**
 * Reservation Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Rooms;
use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\Reviews;
use Session;
use DB;
use DateTime;

class Reservation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reservation';

    protected $appends = ['created_at_timer','status_color','receipt_date', 'dates_subject', 'checkin_arrive', 'checkout_depart', 'guests', 'host_payout', 'guest_payout', 'admin_host_payout', 'admin_guest_payout', 'checkin_md', 'checkout_md', 'checkin_mdy', 'checkout_mdy','check_total'];

    // Check reservation table user_id is equal to current logged in user id
    public static function check_user($id)
    {
      $host_id = Rooms::find($id)->user_id;
      if($host_id == Auth::user()->user()->id)
        return 1;
      else
        return 0;
    }

    // Join with rooms table
    public function rooms()
    {
      return $this->belongsTo('App\Models\Rooms','room_id','id');
    }

    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_id','id');
    }

     public function host_users()
    {
      return $this->belongsTo('App\Models\User','host_id','id');
    }
    
    // Join with currency table
    public function currency()
    {
      return $this->belongsTo('App\Models\Currency','currency_code','code');
    }

    // Join with messages table
    public function messages()
    {
      return $this->belongsTo('App\Models\Messages','id','reservation_id');
    }

    // Join with special_offer table
    public function special_offer()
    {
        return $this->belongsTo('App\Models\SpecialOffer','id','reservation_id')->latest();
    }

    // Join with payouts table
    public function payouts()
    {
        return $this->belongsTo('App\Models\Payouts','id','reservation_id');
    }

    // Join with host_penalty table
    public function host_penalty()
    {
        return $this->belongsTo('App\Models\HostPenalty','id','reservation_id');
    }

    // Join with reviews table
    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews','reservation_id','id');
    }

    // Get Review Details using Review ID
    public function review_details($id)
    {
      return Reviews::find($id);
    }

    // Get Review User Details using User ID
    public function review_user($id)
    {
      if($this->attributes['user_id'] == $id)
        $user_id = $this->attributes['host_id'];
      else
        $user_id = $this->attributes['user_id'];

      return User::find($user_id);
    }

    // Get Review Remaining Days
    public function getReviewDaysAttribute()
    {
      $start_date = $this->attributes['checkout'];
      $end_date = date('Y-m-d', strtotime($this->attributes['checkout'].' +14 days'));

      $datetime1 = new DateTime(date('Y-m-d'));
      $datetime2 = new DateTime($end_date);
      $interval = $datetime1->diff($datetime2);
      $days = $interval->format('%R%a');
      return $days+1;
    }

    // Get Host Payout Email ID
    public function getHostPayoutEmailIdAttribute()
    {
        $payout = PayoutPreferences::where('user_id',$this->attributes['host_id'])->where('default','yes')->get();
        return @$payout[0]->paypal_email;
    }

    // Get Guest Payout Email ID
    public function getGuestPayoutEmailIdAttribute()
    {
        $payout = PayoutPreferences::where('user_id',$this->attributes['user_id'])->where('default','yes')->get();
        return @$payout[0]->paypal_email;
    }

    // Get Host Payout ID
    public function getHostPayoutIdAttribute()
    {
        $payout = Payouts::where('user_id',$this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();
        return @$payout[0]->id;
    }

    // Get Guest Payout ID
    public function getGuestPayoutIdAttribute()
    {
        $payout = Payouts::where('user_id',$this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();
        return @$payout[0]->id;
    }

    // Get Host Payout Preference ID
    public function getHostPayoutPreferenceIdAttribute()
    {
        $payout = PayoutPreferences::where('user_id',$this->attributes['host_id'])->where('default','yes')->get();
        return @$payout[0]->id;
    }

    // Get Guest Payout Preference ID
    public function getGuestPayoutPreferenceIdAttribute()
    {
        $payout = PayoutPreferences::where('user_id',$this->attributes['user_id'])->where('default','yes')->get();
        return @$payout[0]->id;
    }

    // Check Host is eligible or not for amount transfer using Payouts table
    public function getCheckHostPayoutAttribute()
    {
        $check = Payouts::where('reservation_id', $this->attributes['id'])->where('user_type','host')->where('status', 'Completed')->get();

        if($check->count())
          return 'yes';
        else
          return 'no';
    }

    // Check Guest is eligible or not for amount transfer using Payouts table
    public function getCheckGuestPayoutAttribute()
    {
        $check = Payouts::where('reservation_id', $this->attributes['id'])->where('user_type','guest')->where('status', 'Completed')->get();
        
        if($check->count())
          return 'yes';
        else
          return 'no';
    }

    // Get Host Payout Amount
    public function getHostPayoutAttribute()
    {
      $check = Payouts::where('user_id', $this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();
      
      if($check->count())
        return $check[0]->amount;
      else
         return $this->currency_calc('total') - $this->currency_calc('service') - $this->currency_calc('host_fee') + $this->currency_calc('coupon_amount');
    }
       // Get Host/Guest Total and check with the service and coupon amount
    public function getCheckTotalAttribute()
    {
      $host_id =  $this->attributes['host_id'];
      
      if($host_id == @Auth::user()->user()->id)
        return $this->attributes['total'] + $this->attributes['coupon_amount'] - $this->attributes['service'];
      else
        return $this->attributes['total'];
      
    }
    
    // Admin host /Guest payout 
    public function getAdminHostPayoutAttribute()
    {
      $check = Payouts::where('user_id', $this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();
      
      if($check->count())
        return $check[0]->amount;
      else
        return 0;
        
    }

     public function getAdminGuestPayoutAttribute()
    {
      $check = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();

      if($check->count())
        return $check[0]->amount;
      else
        return 0;
    }
    


    // Get Guest Payout Amount
    public function getGuestPayoutAttribute()
    {
      $check = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();

      if($check->count())
        return $check[0]->amount;
      else
        return $this->currency_calc('total');
      //$this->attributes['total'];
    }

    // Get Receipt Date from created_at field
    public function getReceiptDateAttribute()
    {
      return date('D, F d, Y', strtotime($this->attributes['created_at']));
    }

    // Get Date for Email Subject
    public function getDatesSubjectAttribute()
    {
      return date('d F, Y', strtotime($this->attributes['checkin'])).' - '.date('d F, Y', strtotime($this->attributes['checkout']));
    }

    // Get Checkin Date in dmy format
    public function getCheckinDmyAttribute()
    {
      $checkin =  date('D, F d, Y', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Date in dmy format
    public function getCheckoutDmyAttribute()
    {
      $checkout =  date('D, F d, Y', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin Date in dmd format
    public function getCheckinDmdAttribute()
    {
      $checkin =  date('D, M d', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Date in dmy format
    public function getCheckoutDmdAttribute()
    {
      $checkout =  date('D, M d', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin Date in datepicker format
    public function getCheckinDatepickerAttribute()
    {
      $checkin =  date('d-m-Y', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Date in datepicker format
    public function getCheckoutDatepickerAttribute()
    {
      $checkout =  date('d-m-Y', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin Date in mdy format
    public function getCheckinMdyAttribute()
    {
      $checkin =  date('m/d/y', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Date in mdy format
    public function getCheckoutMdyAttribute()
    {
      $checkout =  date('m/d/y', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin Date in dmy format
    public function getCheckinDmySlashAttribute()
    {
      $checkin =  date('d/m/y', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Date in dmy format
    public function getCheckoutDmySlashAttribute()
    {
      $checkout =  date('d/m/y', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin Date in md format
    public function getCheckinMdAttribute()
    {
      $checkin =  date('M d', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkin Arrive Date in md format
    public function getCheckinArriveAttribute()
    {
      $checkin =  date('D, d F, Y', strtotime($this->attributes['checkin']));
      return $checkin;
    }

    // Get Checkout Depart Date in md format
    public function getCheckoutDepartAttribute()
    {
      $checkout =  date('D, d F, Y', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkout Date in md format
    public function getCheckoutMdAttribute()
    {
      $checkout =  date('M d', strtotime($this->attributes['checkout']));
      return $checkout;
    }

    // Get Checkin and Checkout Dates
    public function getDatesAttribute()
    {
      return date('M d', strtotime($this->attributes['checkin'])).' - '.date('d, Y', strtotime($this->attributes['checkout']));
    }

    // Get Created At Timer for Expired
    public function getCreatedAtTimerAttribute()
    {
      $expired_at =  date('Y/m/d H:i:s', strtotime(str_replace('-','/',$this->attributes['created_at']).' +1 day'));
      return $expired_at;
    }

    public function getPerNightAttribute()
    {
        return $this->currency_calc('per_night');
    }
    public function getSubtotalAttribute()
    {
        return $this->currency_calc('subtotal');
    }
    public function getCleaningAttribute()
    {
        return $this->currency_calc('cleaning');
    }
    public function getAdditionalGuestAttribute()
    {
        return $this->currency_calc('additional_guest');
    }
    public function getSecurityAttribute()
    {
        return $this->currency_calc('security');
    }
    public function getServiceAttribute()
    {
        return $this->currency_calc('service');
    }
    public function getHostFeeAttribute()
    {
        return $this->currency_calc('host_fee');
    }
    public function getTotalAttribute()
    {
        return $this->currency_calc('total');
    }
    public function getPayoutAttribute()
    {
        return $this->currency_calc('payout');
    }

    // Get value of Checkin crossed days
    public function getCheckinCrossAttribute()
    {
      $date1=date_create($this->attributes['checkin']);
      $date2=date_create(date('Y-m-d'));
       $diff=date_diff($date1,$date2);
      if($date2 < $date1 )
        return 1;
      else
        return 0;
      // return $diff->format("%a");
    }

    // Get value of Checkout crossed days
    public function getCheckoutCrossAttribute()
    {
      $date1=date_create($this->attributes['checkout']);
      $date2=date_create(date('Y-m-d'));

      if($date2 > $date1 )
        return 1;
      else
        return 0;
    }

    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->first()->code;

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

        return round($usd_amount * $session_rate);
    }

    // Get default currency code if session is not set
    public function getCurrencyCodeAttribute()
    {
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->first()->code;
    }

    public function getOriginalCurrencyCodeAttribute()
    {
        return $this->attributes['currency_code'];
    }

    // Set Reservation Status Color
    public function getStatusColorAttribute()
    {
      if($this->attributes['status'] == 'Accepted')
        return 'success';
      else if($this->attributes['status'] == 'Expired')
        return 'info';
      else if($this->attributes['status'] == 'Pending')
        return 'warning';
      else if($this->attributes['status'] == 'Declined')
        return 'info';
      else if($this->attributes['status'] == 'Cancelled')
        return 'info';
      else if($this->attributes['status'] == '')
        return 'inquiry';
      else
        return '';
    }

    // Get Reservation Status
    public function getStatusAttribute()
    {
      if($this->attributes['status'] == '')
        return 'Inquiry';
      else
        return $this->attributes['status'];
    }

    // Get Guest Count with Plural
    public function getGuestsAttribute()
    {
      if($this->attributes['number_of_guests'] > 1)
      {
        $plural = ($this->attributes['number_of_guests']-1 > 1) ? 's':'';
       return '+'.($this->attributes['number_of_guests']-1).' Guest'.$plural;
      }
    }
}
