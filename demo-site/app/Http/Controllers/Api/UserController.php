<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HomeCities;
use JWTAuth;
use Session;


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


    public function signup_details()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $user  = User::with(['profile_picture'])
            ->whereId($user->id)
            ->select('id', 'first_name', 'last_name', 'email', 'dob')
            ->first();
        $token = JWTAuth::getToken();
        $token = (string)$token;

        $user = ['status'          => '1',
                 'success_message' => '1',
                 'token'           => $token,
                 'id'              => $user->id,
                 'first_name'      => $user->first_name,
                 'last_name'       => $user->last_name,
                 'email'           => $user->email,
                 'dob'             => $user->dob,
                 'image_url'       => $user->profile_picture->src
        ];

        return response()->json(compact('user'));
    }


    public function logout(Request $request)
    {
        JWTAuth::invalidate($request->token);
        Session::flush();

        return response()->json(['success_message' => 'Logout Successfully', 'status_code' => '1']);

    }

}
