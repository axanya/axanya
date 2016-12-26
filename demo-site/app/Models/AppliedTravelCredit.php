<?php

/**
 * Applied Travel Credit Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Applied Travel Credit
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class AppliedTravelCredit extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applied_travel_credit';

    // Get Amount

    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    // Get Original Amount


    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->value('rate');

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->value('code');

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->value('rate');

        return round($usd_amount * $session_rate);
    }


    // Calculation for current currency conversion of given price field

    public function getOriginalAmountAttribute()
    {
        return $this->attributes['amount'];
    }
}
