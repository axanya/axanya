<?php

/**
 * Currency Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Currency extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currency';

    public $timestamps = false;

    protected $appends = ['original_symbol'];

    // Get default currency symbol if session is not set
    public function getSymbolAttribute()
    {
        if(Session::get('symbol'))
           return Session::get('symbol');
        else
           return DB::table('currency')->where('default_currency', 1)->first()->symbol;
    }

    // Get default currency symbol if session is not set
    public function getSessionCodeAttribute()
    {
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->first()->code;
    }

    // Get symbol by where given code
    public static function original_symbol($code)
    {
    	$symbol = DB::table('currency')->where('code', $code)->first()->symbol;
    	return $symbol;
    }

    public static function user_symbol() {
      $code = DB::table('users')->where('id', \Auth::user()->user()->id)->first()->currency_code;
      if(!$code) {
        $code = 'USD';
      }
      $symbol = DB::table('currency')->where('code', $code)->first()->symbol;
    	return $symbol;
    }

    // Get currenct record symbol
    public function getOriginalSymbolAttribute()
    {
        $symbol = $this->attributes['symbol'];
        return $symbol;
    }
}
