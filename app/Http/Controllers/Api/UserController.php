<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HomeCities;
use JWTAuth;

class UserController extends Controller
{
    public function user_details()
    {
    	$user = JWTAuth::parseToken()->authenticate();

    	$user = User::with(['profile_picture'])->whereId($user->id)->first();

    	return response()->json(compact('user'));
    }

    public function home_cities()
    {
    	$home_cities	   = HomeCities::all();

    	return response()->json(compact('home_cities'));
    }
}
