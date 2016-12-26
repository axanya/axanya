<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedWishlists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_wishlists';

    public $timestamps = false;

    // Join with wishlists table
    public function wishlists()
    {
        return $this->belongsTo('App\Models\Wishlists','wishlist_id','id');
    }

    // Join with rooms table
    public function rooms()
    {
        return $this->belongsTo('App\Models\Rooms','room_id','id');
    }

    // Join with rooms_photos table
    public function rooms_photos()
    {
        return $this->hasMany('App\Models\RoomsPhotos','room_id','room_id');
    }

    // Join with rooms_price table
    public function rooms_price()
    {
        return $this->belongsTo('App\Models\RoomsPrice','room_id','room_id');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with profile_picture table
    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture','user_id','user_id');
    }
}
