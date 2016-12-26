<?php

/**
 * Reviews Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Reviews
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reviews;

class Reviews extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_to','id');
    }

    // Join with users table
    public function users_from()
    {
      return $this->belongsTo('App\Models\User','user_from','id');
    }

    // Join with reservation table
    public function reservation()
    {
      return $this->belongsTo('App\Models\Reservation','reservation_id','id');
    }

    // Get updated_at date in fy format
    public function getDateFyAttribute()
    {
        return date('F Y', strtotime($this->attributes['updated_at']));
    }

    // Check give record is Hidden review or not
    public function getHiddenReviewAttribute()
    {
        $reservation_id = $this->attributes['reservation_id'];
        $user_from = $this->attributes['user_from'];
        $user_to = $this->attributes['user_to'];
        $check = Reviews::where(['user_from'=>$user_to, 'user_to'=>$user_from, 'reservation_id'=>$reservation_id])->get();
        if($check->count())
            return false;
        else
            return true;
    }
}
