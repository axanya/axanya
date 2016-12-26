<?php

/**
 * Rooms Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     1.2.1.1
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use DateTime;
use DateTimeZone;
use Config;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Rooms extends Model
{
    use SoftDeletes;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    protected $appends = [
        'steps_count',
        'property_type_name',
        'room_type_name',
        'bed_type_name',
        'photo_name',
        'host_name',
        'reviews_count',
        'overall_star_rating'
    ];

    protected $dates = ['deleted_at'];

    // Check rooms table user_id is equal to current logged in user id
    public static function check_user($id)
    {
    	return Rooms::where(['id' => $id, 'user_id' => Auth::user()->user()->id])->first();
    }

    // Join with rooms_address table
    public function rooms_address()
    {
        return $this->belongsTo('App\Models\RoomsAddress','id','room_id');
    }

    // Join with rooms_price table
    public function rooms_price()
    {
        return $this->belongsTo('App\Models\RoomsPrice','id','room_id');
    }

    // Join with rooms_description table
    public function rooms_description()
    {
        return $this->belongsTo('App\Models\RoomsDescription','id','room_id');
    }

    // Join with saved_wishlists table
    public function saved_wishlists()
    {
        return $this->belongsTo('App\Models\SavedWishlists','id','room_id');
    }

    // Join with reviews table
    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews','room_id','id')->where('user_to', $this->attributes['user_id']);
    }

    // Reviews Count
    public function getReviewsCountAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        return $reviews->count();
    }

    // Overall Reviews Star Rating
    public function getOverallStarRatingAttribute()
    {
        //change add if end else condition
        $api_url = Route::getFacadeRoot()->current()->uri();

        if ($api_url == 'api/explore' || $api_url == 'api/maps')
        {
            $result['rating_value'] = Reviews::where('review_by', 'guest')
                ->where('room_id', $this->attributes['id'])
                ->pluck('rating');
            $result_wishlist        = Wishlists::where('user_id', $this->attributes['id']);

            if ($result_wishlist->count() == 0)
            {
                $result['is_wishlist'] = "No";
            }
            else
            {
                $result['is_wishlist'] = "Yes";
            }

            return $result;

        }
        else
        {

            $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

            $average = @($reviews->sum('rating') / $reviews->count());

            if($average)
            {
                $html = '<div class="star-rating"> <div class="foreground">';

                $whole = floor($average);
                $fraction = $average - $whole;

                for($i=0; $i<$whole; $i++) $html .= ' <i class="icon icon-beach icon-star"></i>';

                if($fraction >= 0.5) $html .= ' <i class="icon icon-beach icon-star-half"></i>';

                $html .= ' </div> <div class="background">';
                $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
                $html .= ' </div> </div>';

                return $html;
            }
            else
                return '';
        }
}

    // Accuracy Reviews Star Rating
    public function getAccuracyStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('accuracy') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Location Reviews Star Rating
    public function getLocationStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('location') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Communication Reviews Star Rating
    public function getCommunicationStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('communication') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Checkin Reviews Star Rating
    public function getCheckinStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('checkin') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Cleanliness Reviews Star Rating
    public function getCleanlinessStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('cleanliness') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Value Reviews Star Rating
    public function getValueStarRatingAttribute()
    {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

        $average = @($reviews->sum('value') / $reviews->count());

        if($average)
        {
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';
            
            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            return $html;
        }
        else
            return '';
    }

    // Get rooms featured photo_name URL
    public function getPhotoNameAttribute()
    {
        $result = RoomsPhotos::where('room_id', $this->attributes['id'])->where('featured','Yes');

        if($result->count() == 0)
            return "room_default_no_photos.png";
        else
            return "rooms/".$this->attributes['id']."/".$result->value('name');
    }
    // Get rooms featured photo_name URL
    public function getSrcAttribute()
    {
        $result = RoomsPhotos::where('room_id', $this->attributes['id'])->where('featured','Yes');

        if($result->count() == 0)
            return url()."/images/room_default_no_photos.png";
        else
            return url()."/images/rooms/".$this->attributes['id']."/".$result->value('name');
    }

    // Get steps_count using sum of rooms_steps_status
    public function getStepsCountAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['id']);

        return 6 - (@$result->basics + @$result->description + @$result->location + @$result->photos + @$result->pricing + @$result->calendar);
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with calendar table
    public function calendar()
    {
        // return $this->hasMany('App\Models\Calendar','room_id','id');
        return $this->hasMany('App\Models\Calendar', 'room_id', 'id')
                ->where('status', 'Not available');
    }

    // Get property_type_name from property_type table
    public function getPropertyTypeNameAttribute()
    {
        return PropertyType::find($this->attributes['property_type'])->name;
    }

    // Get room_type_name from room_type table
    public function getRoomTypeNameAttribute()
    {
        return RoomType::find($this->attributes['room_type'])->name;
    }

    // Get host name from users table
    public function getHostNameAttribute()
    {
        return User::find($this->attributes['user_id'])->first_name;
    }

    // Get bed_type_name from bed_type table
    public function getBedTypeNameAttribute()
    {
        if($this->attributes['bed_type'] != NULL)
            return BedType::find($this->attributes['bed_type'])->name;
        else
            return $this->attributes['bed_type'];
    }

    // Get host user data
    public function scopeUser($query)
    {
        return $query->where('user_id', '=', Auth::user()->user()->id);
    }

    // Get Created at Time for Rooms Listed
    public function getCreatedTimeAttribute()
    {
        $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
        $new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));

        return $new_str->format('M d').' at '.$new_str->format('h:i A');
    }
}
