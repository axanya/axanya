<?php

/**
 * SpecialOffer Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    SpecialOffer
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class SpecialOffer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'special_offer';

    public $timestamps = false;

    public $appends = ['dates_subject', 'checkin_arrive', 'checkout_depart', 'dates'];

    public function setCheckinAttribute($value)
    {
        $this->attributes['checkin'] = date('Y-m-d', strtotime($value));
    }

    public function setCheckoutAttribute($value)
    {
        $this->attributes['checkout'] = date('Y-m-d', strtotime($value));
    }

    public function rooms()
    {
      return $this->belongsTo('App\Models\Rooms','room_id','id');
    }

    // Join with currency table
    public function currency()
    {
      return $this->belongsTo('App\Models\Currency','currency_code','code');
    }

    // Join with messages table
    public function messages()
    {
      return $this->belongsTo('App\Models\Messages','id','special_offer_id');
    }

    public function getPriceAttribute()
    {
        return $this->currency_calc('price');
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

    // Get Date for Email Subject
    public function getDatesSubjectAttribute()
    {
      return date('d F, Y', strtotime($this->attributes['checkin'])).' - '.date('d F, Y', strtotime($this->attributes['checkout']));
    }

    // Get Checkin and Checkout Dates
    public function getDatesAttribute()
    {
      return date('M d', strtotime($this->attributes['checkin'])).' - '.date('d, Y', strtotime($this->attributes['checkout']));
    }
}
