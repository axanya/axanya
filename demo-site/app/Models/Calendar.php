<?php

/**
 * Calendar Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Calendar
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomsPrice;
use App\Models\Currency;
use Session;

class Calendar extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calendar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['room_id', 'price', 'status', 'date', 'notes'];

    protected $appends = ['session_currency_price'];


    // Get result of night price for current currency
    public function getPriceAttribute()
    {
        //return $this->currency_calc('price');
        return $this->attributes['price'];
    }


    public function getSessionCurrencyPriceAttribute()
    {
        return $this->currency_calc('price');
    }

    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    {
        $currency_code = RoomsPrice::where('room_id', $this->attributes['room_id'])->value('currency_code');

        $rate = Currency::whereCode($currency_code)->value('rate');

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->value('code');

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->value('rate');

        return round($usd_amount * $session_rate);
    }

}
