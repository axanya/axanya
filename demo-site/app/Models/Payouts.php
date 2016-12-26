<?php

/**
 * Payouts Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Payouts
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Payouts extends Model
{

    public $appends = ['currency_symbol', 'date'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payouts';

    // Get Payout Amount

    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    // Get Date with new format

    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->value('rate');

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency', 1)->value('code');

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)
            ->value('rate');

        return round($usd_amount * $session_rate);
    }

    // Get Date with new format

    public function getDateAttribute()
    {
        //return date('d-m-Y', strtotime($this->attributes['updated_at']));
        return date(SITE_DATE_FORMAT, strtotime($this->attributes['updated_at']));
    }


    // Calculation for current currency conversion of given price field


    public function getTotalPenaltyAmountAttribute()
    {
        $penalty = 0;

        if($this->attributes['penalty_amount'] != 0) {
                $ex_penalty = explode(',', $this->attributes['penalty_amount']);
                foreach($ex_penalty as $row) {
                    $penalty += $row;
                }
            }

        if($penalty != 0) {
            $rate = Currency::whereCode($this->attributes['currency_code'])->value('rate');

            $usd_amount = $penalty / $rate;

            $default_currency = Currency::where('default_currency',1)->value('code');

            $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->value('rate');

            return round($usd_amount * $session_rate);
        }
        else {
            return 0;
        }
    }

    // Get default currency code if session is not set

    public function getCurrencyCodeAttribute()
    {
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->value('code');
    }

    // Get Currency Symbol
    public function getCurrencySymbolAttribute()
    {
        $default_currency = Currency::where('default_currency',1)->value('code');

        return DB::table('currency')->where('code', (Session::get('currency')) ? Session::get('currency') : $default_currency)->value('symbol');
    }
}
