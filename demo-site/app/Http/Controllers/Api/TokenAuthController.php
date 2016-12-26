<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\UsersVerification;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Hash;
use Validator;
use DateTime;
use Session;

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

        }
        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
        {
 
            return response()->json(['error' => 'token_expired']);

        }
        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
        {
        
            return response()->json(['error' => 'token_invalid']);

        }
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e)
        {
 
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


    public function signup(Request $request, EmailController $email_controller)
    {

        $rules = [
            'email'      => 'required|max:255|email|unique:users',
            'dob'        => 'required|date|date_format:d-m-Y',
            'password'   => 'required',
            'ip'         => 'required',
            'first_name' => 'required',
            'last_name'  => 'required'
        ];

        // Email signup validation custom messages
        $messages = ['required' => ':attribute is required.'];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails())
        {
            //dd($validator->messages()->toArray());
            $error = $validator->messages()->toArray();
            if (isset($error['dob']) && isset($error['email']))
            {
                $user = [
                    'success_message'  => 'Invalid Email && Dob Format',
                    'status_code'      => '0',
                    'validation error' => $error
                ];

                return response()->json($user);

            }
            elseif (isset($error['email']) && ! isset($error['dob']))
            {
                $user = [
                    'success_message'  => 'Invalid Email Id',
                    'status_code'      => '0',
                    'validation error' => $error
                ];

                return response()->json($user);
            }
            elseif ( ! isset($error['email']) && isset($error['dob']))
            {
                $user = [
                    'success_message'  => 'Invalid Dob Format',
                    'status_code'      => '0',
                    'validation error' => $error
                ];

                return response()->json($user);
            }
            elseif (isset($error['ip']))
            {
                $user = ['success_message' => 'Ip Address Must...', 'status_code' => '0'];

                return response()->json($user);
            }
            elseif (isset($error['password']))
            {
                $user = ['success_message' => 'Password Must...', 'status_code' => '0'];

                return response()->json($user);
            }
            elseif (isset($error['first_name']) || isset($error['last_name']))
            {
                $user = [
                    'success_message'  => 'User Name Must...',
                    'status_code'      => '0',
                    'validation error' => $error
                ];

                return response()->json($user);
            }

        }
        else
        {

            $from = new DateTime($request->dob);
            $to   = new DateTime('today');
            $age  = $from->diff($to)->y;

            if ($age < 18)
            {
                $user = [
                    'success_message' => 'You must be 18 or older.',
                    'status_code'     => '0',
                ];

                return response()->json($user);
                exit;
            }
            $clean_ip_address = addslashes(htmlspecialchars(strip_tags(trim($request->ip))));

            // the regular expression for valid ip addresses
            $reg_ex = '/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/';

            if ( ! preg_match($reg_ex, $clean_ip_address))
            {

                return response()->json([
                        'success_message' => 'Not Valid IP Address',
                        'status_code'     => '0'
                    ]);
                exit;

            }

            else
            {
                $result_contry_code = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $request->ip));
                $currency_code      = $result_contry_code['geoplugin_currencyCode'];

                $str_date = $request->dob;
                $date     = DateTime::createFromFormat('d-m-Y', $str_date);
                $dob      = $date->format('Y-m-d');

                $user = new User;

                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->password   = bcrypt($request->password);
                $user->dob        = $dob;

                if ($request->fbid != '')
                {
                    $user->fb_id = $request->fbid;
                }
                if ($request->gpid != '')
                {
                    $user->google_id = $request->gpid;
                }
                if ($request->ip != '')
                {

                    $user->currency_code = $currency_code;
                }
                // $user->device_type  =   $request->device_type;

                $user->save();  // Create a new user

                //Session::put('currency', $currency_code);
                $user_pic = new ProfilePicture;

                if ($request->fbid == '' && $request->gpid == '')
                {
                    $user_pic->user_id      = $user->id;
                    $user_pic->src          = "";
                    $user_pic->photo_source = 'Local';
                    $user_pic->save();  // Create a profile picture record
                }

                if ($request->fbid != '')
                {
                    $user_pic->user_id      = $user->id;
                    $user_pic->src          = $request->profile_pic;
                    $user_pic->photo_source = 'Facebook';
                    $user_pic->save();  // Create a profile picture record
                }

                elseif ($request->gpid != '')
                {
                    $user_pic->user_id      = $user->id;
                    $user_pic->src          = $request->profile_pic;
                    $user_pic->photo_source = 'Google ';
                    $user_pic->save();  // Create a profile picture record
                }

                $user_verification = new UsersVerification;

                $user_verification->user_id = $user->id;

                $user_verification->save();  // Create a users verification record

                $email_controller->welcome_email_confirmation($user);

                $credentials = $request->only('email', 'password');

                try
                {
                    if ( ! $token = JWTAuth::attempt($credentials))
                    {

                        // return response()->json(['error' => 'invalid_credentials']);
                        return response()->json(['success_message' => 'Signup Failed', 'status_code' => '0']);
                    }
                }
                catch (JWTException $e)
                {
                    //return response()->json(['error' => 'could_not_create_token']);
                    return response()->json(['success_message' => 'could_not_create_token', 'status_code' => '0']);
                }
                // if no errors are encountered we can return a JWT
                $user = [
                    'success_message' => 'signup success',
                    'status_code'     => '1',
                    'access_token'    => $token,
                    'first_name'      => $request->first_name,
                    'last_name'       => $request->last_name,
                    'user_image'      => $user->profile_picture->src,
                    'dob'             => $user->dob,
                    'email_id'        => $request->email
                ];

                //return response()->json(compact('user'));
                return response()->json($user);
            }
        }
    }


    public function login(Request $request)
    {
        if (Auth::user()->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $credentials = $request->only('email', 'password');

            try
            {
                if ( ! $token = JWTAuth::attempt($credentials))
                {
                    //return response()->json(['error' => 'invalid_credentials']);
                    return response()->json([
                        'success_message' => "Those credentials don't look right. Please try again.",
                        'status_code'     => '0'
                    ]);

                }
            }
            catch (JWTException $e)
            {

                //return response()->json(['error' => 'could_not_create_token']);
                return response()->json(['success_message' => 'could_not_create_token', 'status_code' => '0']);

            }
            //echo Session::get('currency'); exit;
            $user = User::with(['profile_picture'])
                ->whereemail($request->email)
                ->select('id', 'first_name', 'last_name', 'email', 'dob', 'users.currency_code')
                ->first();
            // if no errors are encountered we can return a JWT
            Session::put('currency', $user->currency_code);

            $user = [
                'success_message' => 'Login Success',
                'status_code'     => '1',
                'access_token'    => $token,
                'first_name'      => $user->first_name,
                'last_name'       => $user->last_name,
                'user_image'      => $user->profile_picture->src,
                'dob'             => $user->dob,
                'email_id'        => $user->email
            ];

            return response()->json($user);

        }
        else
        {
            return response()->json([
                'success_message' => "Those credentials don't look right. Please try again.",
                'status_code'     => '0'
            ]);

        }

    }


    public function emailvalidation(Request $request)
    {
        $rules = ['email' => 'required|max:255|email|unique:users'];

        // Email signup validation custom messages
        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails())
        {
            $user = ['success_message' => 'Email Already exist', 'status_code' => '0'];

            return response()->json($user);
        }
        else
        {
            $user = ['success_message' => 'Emailvalidation Success', 'status_code' => '1'];

            return response()->json($user);
        }
    }


    public function forgotpassword(Request $request, EmailController $email_controller)
    {
        $rules = ['email' => 'required|email|exists:users,email'];

        $messages = ['required' => ':attribute is required.'];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails())
        {
            $user = ['success_message' => 'Invalid Emailid', 'status_code' => '0'];

            return response()->json($user);
        }
        else
        {
            $user = User::whereEmail($request->email)->first();
            $email_controller->forgot_password($user);
            $user = [
                'success_message' => 'Reset password link send to your Email id',
                'status_code'     => '1'
            ];

            return response()->json($user);
        }
    }
}
 
