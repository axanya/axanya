<?php

/**
 * Messages Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Messages
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;
use Auth;
use Config;

class Messages extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    protected $appends = ['created_time','pending_count','archived_count','reservation_count','unread_count','stared_count','all_count','host_check','guest_check'];

    // Get All Messages
    public static function all_messages($user_id)
    {
        return Messages::where('user_to', $user_id)->groupby('user_from', 'user_to')->orderBy('id', 'desc')->get();
    }

    // Get All Message Count
    public  function getAllCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->where('message_type','!=',5)->get()->count();
    }

    // Get Stared Message Count
    public  function getStaredCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->where('star', 1)->where('message_type','!=',5)->get()->count();
    }

    // Get Unread Message Count
    public  function getUnreadCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->where('read', 0)->where('message_type','!=',5)->get()->count();
    }

    // Get Reservation Message Count
    public  function getReservationCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'] )->where('reservation_id','!=', 0)->where('message_type','!=',5)->get()->count();
    }

    // Get Archived Message Count
    public  function getArchivedCountAttribute()
    {
        return Messages::where('user_to', $this->attributes['user_to'])->where('archive', 1)->where('message_type','!=',5)->get()->count();
    }   

    // Get Pending Message Count
    public function getPendingCountAttribute()
    {
        return Reservation::join('messages', function($join)
            {
                $join->on('messages.reservation_id', '=', 'reservation.id')->where('reservation.status','=', 'Pending')->where('messages.user_to','=', Auth::user()->user()->id)->where('message_type','!=',5);
            })->get()->count();
    }

    // Host Check
    public function getHostCheckAttribute()
    {
        $check =  Reservation::where('room_id', $this->attributes['room_id'])->where('host_id', Auth::user()->user()->id )->get();

        if(count($check) !=0)
            return 1;
        else
            return 0;
    }

    // Guest Check
    public function getGuestCheckAttribute()
    {
        $check =  Reservation::where('room_id', $this->attributes['room_id'])->where('host_id', Auth::user()->user()->id )->get();

        if(count($check) ==0)
            return 1;
        else
            return 0;
    }

    // Join to User table
    public function user_details()
    {
        return $this->belongsTo('App\Models\User','user_from','id');
    }
    
    // Join to Reservation table
    public function reservation()
    {
        return $this->belongsTo('App\Models\Reservation','reservation_id','id');
    }

    // Join to Reservation Alteration table
    public function reservation_alteration()
    {
        return $this->belongsTo('App\Models\ReservationAlteration','reservation_id','reservation_id');
    }

    // Join to Rooms Address table
    public function rooms_address()
    {
        return $this->belongsTo('App\Models\RoomsAddress','room_id','room_id');
    }

    // Join to Special Offer table
    public function special_offer()
    {
        return $this->belongsTo('App\Models\SpecialOffer','special_offer_id','id');
    }

    // Get Created at Time for Message
    public function getCreatedTimeAttribute()
    {
        $new_str = new DateTime($this->attributes['created_at'], new DateTimeZone(Config::get('app.timezone')));
        $new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));

        if(date('d-m-Y') == date('d-m-Y',strtotime($this->attributes['created_at']))) return $new_str->format('h:i A');
        else
            return $new_str->format(SITE_DATE_FORMAT);
    }
}
