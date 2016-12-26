<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
 
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\UsersVerification;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Hash;
 

class TokenAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
 
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }
 
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }
 
    public function getAuthenticatedUser()
    {
        try {
        
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found']);
            }
 
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
 
            return response()->json(['error' => 'token_expired']);
 
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        
            return response()->json(['error' => 'token_invalid']);
 
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
 
            return response()->json(['error' => 'token_absent']);
 
        }
        
        return response()->json(compact('user'));
    }
 
    public function register(Request $request, EmailController $email_controller) 
    {
        $user = new User;

        $user->first_name   =   $request->first_name;
        $user->last_name    =   $request->last_name;
        $user->email        =   $request->email;
        $user->password     =   bcrypt($request->password);
        $user->dob          =   $request->birthday;

        $user->save();  // Create a new user

        $user_pic = new ProfilePicture;

        $user_pic->user_id      =   $user->id;
        $user_pic->src          =   "";
        $user_pic->photo_source =   'Local';

        $user_pic->save();  // Create a profile picture record

        $user_verification = new UsersVerification;

        $user_verification->user_id      =   $user->id;

        $user_verification->save();  // Create a users verification record

        $email_controller->welcome_email_confirmation($user);

        $credentials = $request->only('email', 'password');
 
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials']);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token']);
        }
 
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    public function token(Request $request){

        $token = JWTAuth::refresh($request->token);
        
        return response()->json(['token' => $token], 200);
    }
}
 
 
