<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Wishlists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wishlists';

    public $timestamps = false;

    public $appends = ['rooms_count', 'all_rooms_count'];

    // Join with saved_wishlists table
    public function saved_wishlists()
    {
        return $this->hasMany('App\Models\SavedWishlists','wishlist_id','id');
    }

    public function getRoomsCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('wishlist_id', $this->attributes['id'])->where('user_id', $this->attributes['user_id'])->count();
    }

    public function getAllRoomsCountAttribute()
    {
    	return @DB::table('saved_wishlists')->where('wishlist_id', $this->attributes['id'])->count();
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
