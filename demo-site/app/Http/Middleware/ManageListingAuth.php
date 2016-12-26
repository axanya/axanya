<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Models\Rooms;
use Illuminate\Support\Facades\Auth;

class ManageListingAuth
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    /*Route::group(['middleware' => 'manage_listing_auth'] , function(){ });*/

    public function handle($request, Closure $next)
    {
        $user_id = Auth::user()->user()->id;
        $room_id = $request->segment(2);
        $room    = Rooms::find($room_id);
        if ($room->user_id == $user_id)
        {
            return $next($request);
        }
        else
        {
            return json_encode(['redirect' => url('rooms')]);
        }
    }

}
