<?php

/**
 * Email Settings Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Email Settings
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://abv.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class HostPenalty extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_penalty';

    // Get Penalty Amount
    public function getConvertedAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    // Get Penalty Remaining Amount


    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->value('rate');

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->value('code');

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->value('rate');

        return round($usd_amount * $session_rate);
    }


    // Calculation for current currency conversion of given price field

    public function getConvertedRemainAmountAttribute()
    {
        return $this->currency_calc('remain_amount');
    }
}
