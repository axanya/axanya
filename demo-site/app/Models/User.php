<?php

/**
 * User Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Messages;
use DateTime;
use Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'dob'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['dob_dmy', 'age', 'full_name', 'primary_phone_number_protected'];

    protected $dates = ['deleted_at'];

    // Join with profile_picture table

    public static function count()
    {
        return DB::table('users')->whereStatus('Active')->count();
    }


    // Join with users_verification table

    public static function clearUserSession($user_id)
    {
        $session_id = Session::getId();

        $sessions = DB::table('sessions')->where('user_id', $user_id)->where('id', '!=', $session_id)->delete();

        $current_session = DB::table('sessions')->where('id', $session_id)->first();
        if ($current_session)
        {
            $current_session_data = unserialize(base64_decode($current_session->payload));
            foreach ($current_session_data as $key => $value)
            {
                if ('login_user_' == substr($key, 0, 11))
                {
                    if (Session::get($key) == $user_id)
                    {
                        Session::forget($key);
                        Session::save();
                        DB::table('sessions')->where('id', $session_id)->update(['user_id' => null]);;
                    }
                }
            }
        }

        return true;
    }


    // Join with users_phone_numbers table

    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture','id','user_id');
    }

    // Join with saved_wishlists table

    public function users_verification()
    {
        return $this->belongsTo('App\Models\UsersVerification','id','user_id');
    }

    // Join with wishlists table

    public function users_phone_numbers()
    {
        return $this->hasMany('App\Models\UsersPhoneNumbers', 'user_id', 'id');
    }


    // Join with referrals table

    public function saved_wishlists()
    {
        return $this->belongsTo('App\Models\SavedWishlists','id','user_id');
    }

    // Inbox unread message count

    public function wishlists()
    {
        return $this->belongsTo('App\Models\Wishlists','id','user_id');
    }

    // Inbox messages

    public function referrals()
    {
        return $this->belongsTo('App\Models\Referrals','id','user_id');
    }

    // Join with reviews table

    public function inbox_count()
    {
        return Messages::where('user_to', $this->attributes['id'])->where('read', 0)->count();
    }

    // Get status Active users count

    public function inbox()
    {
        $user_id = $this->attributes['id'];
        return Messages::whereIn('id', function($query) use($user_id)
        {
            $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('read',0)->orderBy('id','desc')->get();
    }

    // Convert y-m-d date of birth date into d-m-y

    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews','user_to','id');
    }


    public function getDobDmyAttribute()
    {
        if(@$this->attributes['dob'] != '0000-00-00')
            return date('d-m-Y', strtotime(@$this->attributes['dob']));
        else
            return '';
    }


    public function getAgeAttribute()
    {
        $dob = @$this->attributes['dob'];
        if(!empty($dob) && $dob != '0000-00-00')
        {
            $birthdate = new DateTime($dob);
            $today   = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        }
        else
        {
            return 0;
        }
    }


    public function getPrimaryPhoneNumberProtectedAttribute()
    {
        $primary_phone_number_protected = '';
        $users_phone_numbers            = UsersPhoneNumbers::where('user_id', $this->attributes['id'])
            ->where('status', 'Confirmed')
            ->first();

        return @$users_phone_numbers->phone_number_protected;
    }


    public function getSinceAttribute()
    {
        return date('F Y', strtotime($this->attributes['created_at']));
    }


    public function getFullNameAttribute()
    {
        return ucfirst(@$this->attributes['first_name']).' '.ucfirst(@$this->attributes['last_name']);
    }


    public function getFirstNameAttribute()
    {
        return ucfirst($this->attributes['first_name']);
    }


    public function getLastNameAttribute()
    {
        return ucfirst($this->attributes['last_name']);
    }
}
