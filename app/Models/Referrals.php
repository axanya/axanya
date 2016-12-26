<?php

/**
 * Referrals Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Referrals
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Referrals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referrals';

    public function getCreditedAmountAttribute()
    {
    	return $this->currency_calc('credited_amount');
    }

    public function getFriendCreditedAmountAttribute()
    {
    	return $this->currency_calc('friend_credited_amount');
    }

    public function getIfFriendGuestAmountAttribute()
    {
    	return $this->currency_calc('if_friend_guest_amount');
    }

    public function getIfFriendHostAmountAttribute()
    {
    	return $this->currency_calc('if_friend_host_amount');
    }

    public function getCreditableAmountAttribute()
    {
    	return $this->currency_calc('creditable_amount');
    }

    // Join with currency table
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_code','code');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with users table
    public function friend_users()
    {
        return $this->belongsTo('App\Models\User','friend_id','id');
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
}
