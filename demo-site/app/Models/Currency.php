<?php

/**
 * Currency Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Currency extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currency';

    protected $appends = ['original_symbol'];

    // Get default currency symbol if session is not set

    public static function original_symbol($code)
    {
        $symbol = DB::table('currency')->where('code', $code)->value('symbol');

        return $symbol;
    }


    // Get default currency symbol if session is not set


    public function getSymbolAttribute()
    {
        if(Session::get('symbol'))
           return Session::get('symbol');
        else
           return DB::table('currency')->where('default_currency', 1)->value('symbol');
    }


    // Get symbol by where given code


    public function getSessionCodeAttribute()
    {
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->value('code');
    }

    // Get currenct record symbol

    public function getOriginalSymbolAttribute()
    {
        $symbol = $this->attributes['symbol'];
        return $symbol;
    }
}
