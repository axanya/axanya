<?php

/**
 * PayoutPreferences Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    PayoutPreferences
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;
use Config;
use Auth;

class PayoutPreferences extends Model
{

    public $appends = ['updated_time', 'updated_date'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payout_preferences';

    // Get Updated time for Payout Information

    public function getUpdatedTimeAttribute()
    {
        $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
        $new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));

        return $new_str->format('d M').' at '.$new_str->format('H:i');
    }

    // Get Updated date for Payout Information
    public function getUpdatedDateAttribute()
    {
        $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
        $new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));

        return $new_str->format('d F, Y');
    }

    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_id','id');
    }
}
