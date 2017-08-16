<?php

/**
 * User Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SmsController;
use App\Http\Helper\FacebookHelper;
use App\Http\Start\Helpers;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Messages;
use App\Models\PasswordResets;
use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\PlaceReviews;
use App\Models\Places;
use App\Models\ProfilePicture;
use App\Models\References;
use App\Models\Referrals;
use App\Models\ReferralSettings;
use App\Models\Reservation;
use App\Models\Reviews;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\Timezone;
use App\Models\User;
use App\Models\UsersPhoneNumber;
use App\Models\UsersVerification;
use App\Models\Wishlists;
use Auth;
use Config; // This package have all social media API integration
use DateTime;
use DB;
use Excel;
use Hash;
use Illuminate\Http\Request;
use Image;
use Mail;
use Session;

// Facebook API
use Socialite;
use Validator;

class UserController extends Controller {
	protected $helper; // Global variable for Helpers instance
	private $fb; // Global variable for FacebookHelper instance

	public function __construct(FacebookHelper $fb) {
		$this->fb = $fb;
		$this->helper = new Helpers;
		if (Auth::user()->user()) {
			date_default_timezone_set(Auth::user()->user()->timezone);
		}
	}

	/**
	 * Create a new Email signup user
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function create(Request $request, EmailController $email_controller, SmsController $sms) {

		// Email signup validation rules
		$rules = array(
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'email' => 'required|max:255|email|unique:users',
			'password' => 'required|min:6',
			'birthday_day' => 'required',
			'birthday_month' => 'required',
			'birthday_year' => 'required',
			'agree_to_policy' => 'required',
			// 'phone_code' => 'required',
			// 'phone_number' => 'required',
		);

		// Email signup validation custom messages
		$messages = array(
			'required' => ':attribute is required.',
			'birthday_day.required' => 'Select your birth date to continue.',
			'birthday_month.required' => 'Select your birth date to continue.',
			'birthday_year.required' => 'Select your birth date to continue.',
			'agree_to_policy.required' => 'Please accept Axanya\'s Terms and Policies.',
			// 'phone_number.required' => 'Phone number is required.',
		);

		// Email signup validation custom Fields name
		$niceNames = array(
			'first_name' => 'First name',
			'last_name' => 'Last name',
			'email' => 'Email',
			'password' => 'Password',
			'agree_to_policy' => 'Agree to Policy'
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {

			$birthday_year = $request->birthday_year;
			$birthday_month = $request->birthday_month < 10 ? '0' . $request->birthday_month : $request->birthday_month;
			$birthday_day = $request->birthday_day < 10 ? '0' . $request->birthday_day : $request->birthday_day;
			$dob = $birthday_year . '-' . $birthday_month . '-' . $birthday_day;

			if( time() < strtotime( '+18 years', strtotime( $dob ) ) ) {
				$validator->getMessageBag()->add( 'birthday', 'You have to be 18+ years old.' );
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			}

			$user = new User;

			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
			$user->dob = $request->birthday_year . '-' .
			$request->birthday_month . '-' .
			$request->birthday_day; // Date format - Y-m-d

			$user->save(); // Create a new user

			//insert user's phone information
			$code = $this->get_rand_string(4);
			$number = '+' . $request->phone_code . $request->phone_number;

			$message = $code . " is your Axanya Code. Please enter this code to confirm your mobile number.";
			$res = $sms->send_sms($number, $message);

			$insert['user_id'] = $user->id;
			$insert['phone_code'] = $request->phone_code;
			$insert['phone_number'] = $request->phone_number;
			$insert['otp'] = $code;
			$insert['status'] = 'Pending';
			$insert['send_count'] = 1;

			UsersPhoneNumber::insert($insert);

			$user_pic = new ProfilePicture;

			$user_pic->user_id = $user->id;
			$user_pic->src = "";
			$user_pic->photo_source = 'Local';

			$user_pic->save(); // Create a profile picture record

			$user_verification = new UsersVerification;

			$user_verification->user_id = $user->id;

			$user_verification->save(); // Create a users verification record

			$email_controller->welcome_email_confirmation($user);

			if (Session::get('referral')) {

				$referral_settings = ReferralSettings::first();

				$referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

				if ($referral_check < $referral_settings->value(1)) {
					$referral = new Referrals;

					$referral->user_id = Session::get('referral');
					$referral->friend_id = $user->id;
					$referral->friend_credited_amount = $referral_settings->value(4);
					$referral->if_friend_guest_amount = $referral_settings->value(2);
					$referral->if_friend_host_amount = $referral_settings->value(3);
					$referral->creditable_amount = $referral_settings->value(2) + $referral_settings->value(3);
					$referral->currency_code = $referral_settings->value(5, 'code');

					$referral->save();

					Session::forget('referral');
				}
			}

			Session::put('is_new_user', 'yes');

			if (Auth::user()->attempt(['email' => $request->email, 'password' => $request->password])) {
				$this->helper->flash_message('success', trans('messages.login.reg_successfully') . ' ' . trans('messages.new.pls_confirm_email')); // Call flash message function
				return redirect()->intended('dashboard'); //dashboard Redirect to dashboard page
			} else {
				$this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
				return redirect('login'); // Redirect to login page
			}
		}
	}

	public function mobile_verification(Request $request, SmsController $sms) {
		global $app_mode;
		$data['app_mode'] = $app_mode;

		$user_id = Auth::user()->user()->id;
		$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('long_name', 'ASC')->get();

		if ($request->phone_id && $request->phone_id != '') {
			$data['phone_info'] = UsersPhoneNumber::where(['user_id' => $user_id, 'id' => $request->phone_id])->first();
		} else {
			$data['phone_info'] = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();
		}
		$data['title'] = 'Mobile verification ';

		return view('users.mobile_verification', $data);
	}

	/**
	 * function for get all phone number
	 */
	public function phone_list(Request $request) {
		$data['title'] = trans('messages.profile.phone_list');
		$user_id = Auth::user()->user()->id;
		$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('phone_code', 'ASC')->get();
		$data['phone_info'] = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'ASC')->get();

		return view('users.phone_list', $data);
	}

	/**
	 * function for get all phone number
	 */
	public function references(Request $request) {
		$data['title'] = trans_choice('messages.header.reference', 2);
		$user_id = Auth::user()->user()->id;
		$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('phone_code', 'ASC')->get();
		$data['users_info'] = References::where(['user_id' => $user_id])->orderBy('id', 'ASC')->get();

		return view('users.reference_list', $data);
	}

	public function post_mobile_verification(Request $request, SmsController $sms) {
		global $app_mode;

		$rooms = Rooms::find($request->id);
		$user_id = Auth::user()->user()->id;

		if ($request->type == 'resend') {
			$code = $this->get_rand_string(4);
			$phone_info = UsersPhoneNumber::find($request->phone_id);
			// $number = '+' . $phone_info->phone_code . $phone_info->phone_number;
			$number = $phone_info->phone_number;

			$message = $code . " is your Axanya Code. Please enter this code to confirm your mobile number.";
			$res = $sms->send_sms($number, $message);

			if ($res['success']) {

				$phone_info->otp = $code;
				$phone_info->status = "Pending";
				$phone_info->send_count = $phone_info->send_count + 1;
				$phone_info->save();

				if ($app_mode == 'production') {
					$code = '';
				}
				$response = ['success' => 'true', 'new_code' => $code];
			} else {
				$response = ['success' => 'false', 'msg' => $res['msg']];
			}

		} else if ($request->type == 'verify') {
			$phone_info = UsersPhoneNumber::find($request->phone_id);

			$prev_code = $phone_info->otp;
			$new_code = $request->verification_code;
			if ($new_code == $prev_code) {
				$phone_info->status = 'Confirmed';
				$phone_info->save();

				UsersVerification::where(['user_id' => $user_id])->update(['phone' => 'yes']);

				if (Session::get('is_new_user') == 'yes') {
					Session::put('is_new_user', '');
					$redirect_url = url() . '/users/edit';
					$response = ['success' => 'true', 'msg' => "Verification done successfully.", 'redirect_url' => $redirect_url, 'is_new_user' => 'yes'];
					return json_encode($response);
				} else {
					$response = ['success' => 'true', 'msg' => "Verification done successfully.", 'is_new_user' => 'no'];
					return json_encode($response);
				}

			} else {
				$response = ['success' => 'false', 'msg' => "Invalid code. Please try again, or request a new code."];
				return json_encode($response);
			}
		} else if ($request->type == 'change') {
			// $number = '+' . $request->phone_code . $request->phone_number;
			$number = $request->phone_number;

			$res = $sms->check_number($number);
			if ($res['success']) {
				$phone_info = UsersPhoneNumber::where(['user_id' => $user_id])->first();
				if ($phone_info) {
					$code = $this->get_rand_string(4);

					$message = $code . " is your Axanya Code. Please enter this code to confirm your mobile number.";
					$res = $sms->send_sms($number, $message);
					if ($res['success']) {
						$insert['user_id'] = $user_id;
						$insert['phone_code'] = $request->phone_code;
						$insert['phone_number'] = $request->phone_number;
						$insert['otp'] = $code;
						$insert['status'] = 'Pending';
						$insert['send_count'] = 1;

						UsersPhoneNumber::where(['id' => $phone_info->id])->update($insert);

						UsersVerification::where(['user_id' => $user_id])->update(['phone' => 'no']);

						//$last_id = UsersPhoneNumber::insertGetId($insert);
						if ($app_mode == 'production') {
							$code = '';
						}
						$redirect_url = url() . '/verification';
						$response = ['success' => 'true', 'new_code' => $code, 'redirect_url' => $redirect_url];
					} else {
						$response = ['success' => 'false', 'msg' => $res['msg']];
					}

					return json_encode($response);
				} else {
					$code = $this->get_rand_string(4);

					$message = $code . " is your Axanya Code. Please enter this code to confirm your mobile number.";
					$res = $sms->send_sms($number, $message);
					if ($res['success']) {
						$insert['user_id'] = $user_id;
						$insert['phone_code'] = $request->phone_code;
						$insert['phone_number'] = $request->phone_number;
						$insert['otp'] = $code;
						$insert['status'] = 'Pending';
						$insert['send_count'] = 1;

						$last_id = UsersPhoneNumber::insertGetId($insert);
						if ($app_mode == 'production') {
							$code = '';
						}
						$redirect_url = url() . '/verification';
						$response = ['success' => 'true', 'new_code' => $code, 'redirect_url' => $redirect_url];
					} else {
						$response = ['success' => 'false', 'msg' => $res['msg']];
					}

					return json_encode($response);

				}
			} else {
				$response = ['success' => 'false', 'msg' => $res['msg']];
				return json_encode($response);
			}

		}

		return json_encode($response);
	}

	/**
	 * Email users Login authentication
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function authenticate(Request $request) {
		// Email login validation rules
		$rules = array(
			'email' => 'required|email',
			'password' => 'required',
		);

		// Email login validation custom messages
		$messages = array(
			'required' => ':attribute is required.',
		);

		// Email login validation custom Fields name
		$niceNames = array(
			'email' => 'Email',
			'password' => 'Password',
		);

		// set the remember me cookie if the user check the box
		$remember = ($request->remember_me) ? true : false;

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {
			// Get user status
			$users = User::where('email', $request->email)->first();

			// Check user is active or not
			if (@$users->status != 'Inactive') {
				if (Auth::user()->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
					return redirect()->intended('dashboard'); // Redirect to dashboard page
				} else {
					$this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
					return redirect('login'); // Redirect to login page
				}
			} else // Call Disabled view file for Inactive user
			{
				$data['title'] = 'Disabled ';
				return view('users.disabled', $data);
			}
		}
	}

	/**
	 * Facebook User Registration and Login
	 *
	 * @return redirect     to dashboard page
	 */
	public function facebookAuthenticate(Request $request, EmailController $email_controller) {
		if ($request['error']) {
			$this->helper->flash_message('danger', $request['error_description']); // Call flash message function
			return redirect('login'); // Redirect to login page
		}
		Session::put('is_new_user', '');
		$this->fb->generateSessionFromRedirect(); // Generate Access Token Session After Redirect from Facebook

		$response = $this->fb->getData(); // Get Facebook Response

		$userNode = $response->getGraphUser(); // Get Authenticated User Data

		$email = ($userNode->getProperty('email') == '') ? $userNode->getId() . '@fb.com' : $userNode->getProperty('email');

		$user = User::where('email', $email); // Check Facebook User Email Id is exists

		if ($user->count() > 0) // If there update Facebook Id
		{
			$user = User::where('email', $email)->first();

			$user->fb_id = $userNode->getId();
			$user->fb_access_token = $_SESSION['facebook_access_token'];

			$user->save(); // Update a Facebook id

			$user_id = $user->id; // Get Last Updated Id
		} else // If not create a new user without Password
		{
			$user = User::where('email', $email)->withTrashed();

			if ($user->count() > 0) {
				$data['title'] = 'Disabled ';
				return view('users.disabled', $data);
			}

			$user = new User;

			// New user data
			$user->first_name = $userNode->getFirstName();
			$user->last_name = $userNode->getLastName();
			$user->email = $email;
			$user->fb_id = $userNode->getId();
			$user->fb_access_token = $_SESSION['facebook_access_token'];
			$user->dob = date('Y-m-d', strtotime($userNode->getProperty('user_birthday')));

			$user->save(); // Create a new user

			$user_id = $user->id; // Get Last Insert Id

			$user_pic = new ProfilePicture;

			$user_pic->user_id = $user_id;
			$user_pic->src = "https://graph.facebook.com/" . $userNode->getId() . "/picture?type=large";
			$user_pic->photo_source = 'Facebook';

			$user_pic->save(); // Save Facebook profile picture

			$user_verification = new UsersVerification;

			$user_verification->user_id = $user->id;
			$user_verification->email = 'yes';

			$user_verification->save(); // Create a users verification record

			// $email_controller->welcome_email_confirmation($user);

			if (Session::get('referral')) {
				$referral_settings = ReferralSettings::first();

				$referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

				if ($referral_check < $referral_settings->value(1)) {
					$referral = new Referrals;

					$referral->user_id = Session::get('referral');
					$referral->friend_id = $user->id;
					$referral->friend_credited_amount = $referral_settings->value(4);
					$referral->if_friend_guest_amount = $referral_settings->value(2);
					$referral->if_friend_host_amount = $referral_settings->value(3);
					$referral->creditable_amount = $referral_settings->value(2) + $referral_settings->value(3);
					$referral->currency_code = $referral_settings->value(5, 'code');

					$referral->save();

					Session::forget('referral');
				}
			}
			Session::put('is_new_user', 'yes');
		}

		$users = User::where('id', $user_id)->first();

		if (@$users->status != 'Inactive') {
			if (Auth::user()->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
			{
				if (Session::get('is_new_user') == 'yes') {
					// return redirect()->intended('verification?type=new');
					return redirect()->intended('dashboard');
				} else {
					return redirect()->intended('dashboard'); // Redirect to dashboard page
				}

			} else {
				$this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
				return redirect('login'); // Redirect to login page
			}
		} else // Call Disabled view file for Inactive user
		{
			$data['title'] = 'Disabled ';
			return view('users.disabled', $data);
		}
	}

	/**
	 * Google User redirect to Google Authentication page
	 *
	 * @return redirect     to Google page
	 */
	public function googleLogin() {
		return Socialite::with('google')->redirect();
	}

	/**
	 * Google User Registration and Login
	 *
	 * @return redirect     to dashboard page
	 */
	public function googleAuthenticate(EmailController $email_controller) {
		$userNode = Socialite::with('google')->user();

		if (Session::get('verification') == 'yes') {
			return redirect('googleConnect/' . $userNode->getId());
		}

		$ex_name = explode(' ', $userNode->getName());
		$firstName = $ex_name[0];
		$lastName = $ex_name[1];

		$email = ($userNode->getEmail() == '') ? $userNode->getId() . '@gmail.com' : $userNode->getEmail();

		$user = User::where('email', $email); // Check Google User Email Id is exists

		if ($user->count() > 0) // If there update Google Id
		{
			$user = User::where('email', $email)->first();

			$user->google_id = $userNode->getId();

			$user->save(); // Update a Google id

			$user_id = $user->id; // Get Last Updated Id
		} else // If not create a new user without Password
		{
			$user = User::where('email', $email)->withTrashed();

			if ($user->count() > 0) {
				$data['title'] = 'Disabled ';
				return view('users.disabled', $data);
			}

			$user = new User;

			// New user data
			$user->first_name = $firstName;
			$user->last_name = $lastName;
			$user->email = $email;
			$user->google_id = $userNode->getId();

			$user->save(); // Create a new user

			$user_id = $user->id; // Get Last Insert Id

			$user_pic = new ProfilePicture;

			$user_pic->user_id = $user_id;
			$user_pic->src = $userNode->getAvatar();
			$user_pic->photo_source = 'Google';

			$user_pic->save(); // Save Google profile picture

			$user_verification = new UsersVerification;

			$user_verification->user_id = $user->id;
			$user_verification->email = 'yes';

			$user_verification->save(); // Create a users verification record

			// $email_controller->welcome_email_confirmation($user);

			if (Session::get('referral')) {

				$referral_settings = ReferralSettings::first();

				$referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

				if ($referral_check < $referral_settings->value(1)) {
					$referral = new Referrals;

					$referral->user_id = Session::get('referral');
					$referral->friend_id = $user->id;
					$referral->friend_credited_amount = $referral_settings->value(4);
					$referral->if_friend_guest_amount = $referral_settings->value(2);
					$referral->if_friend_host_amount = $referral_settings->value(3);
					$referral->creditable_amount = $referral_settings->value(2) + $referral_settings->value(3);
					$referral->currency_code = $referral_settings->value(5, 'code');

					$referral->save();

					Session::forget('referral');
				}
			}
			Session::put('is_new_user', 'yes');
		}

		$users = User::where('id', $user_id)->first();

		if (@$users->status != 'Inactive') {
			if (Auth::user()->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
			{
				if (Session::get('is_new_user') == 'yes') {
					// return redirect()->intended('verification?type=new');
					return redirect()->intended('dashboard');
				} else {
					return redirect()->intended('dashboard'); // Redirect to dashboard page
				}
			} else {
				$this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
				return redirect('login'); // Redirect to login page
			}
		} else // Call Disabled view file for Inactive user
		{
			$data['title'] = 'Disabled ';
			return view('users.disabled', $data);
		}
	}

	/**
	 * Load Dashboard view file
	 *
	 * @return dashboard view file
	 */
	public function dashboard() {
		$user_id = Auth::user()->user()->id;
		$data['user_id'] = $user_id;

		$data['all_message'] = Messages::whereIn('id', function ($query) {
			$query->select(DB::raw('max(id)'))
				->from('messages')->groupby('reservation_id');
		})->with(['user_details' => function ($query) {
			$query->with('profile_picture');
		}])->with(['reservation' => function ($query) {
			$query->with('currency');
		}])->with('rooms_address')->where('user_to', $data['user_id'])->where('read', 0)->orderBy('id', 'desc')->get();

		$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('long_name', 'ASC')->get();
		$phone_info = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();
		if(!$phone_info) {
			$insert['user_id'] = $user_id;
			$insert['phone_code'] = '0';
			$insert['phone_number'] = '';
			$insert['otp'] = '0000';
			$insert['status'] = 'Pending';
			$insert['send_count'] = 1;

			UsersPhoneNumber::insert($insert);
		}
		$data['phone_info'] = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();
		// var_dump($data['phone_info']);
		// print_r($data);

		return view('users.guest_dashboard', $data);
	}

	/**
	 * Load Forgot Password View and Send Reset Link
	 *
	 * @return view forgot password page / send mail to user
	 */
	public function forgot_password(Request $request, EmailController $email_controller) {
		if (!$_POST) {
			return view('home.forgot_password');
		} else {
			// Email validation rules
			$rules = array(
				'email' => 'required|email|exists:users,email',
			);

			// Email validation custom messages
			$messages = array(
				'required' => ':attribute is required.',
				'exists' => 'No account exists for this email.',
			);

			// Email validation custom Fields name
			$niceNames = array(
				'email' => 'Email',
			);

			$validator = Validator::make($request->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user = User::whereEmail($request->email)->first();

				$email_controller->forgot_password($user);

				$this->helper->flash_message('success', trans('messages.login.reset_link_sent', ['email' => $user->email])); // Call flash message function
				return redirect('login');
			}
		}
	}

	/**
	 * Set Password View and Update Password
	 *
	 * @param array $request Input values
	 * @return view set_password / redirect to Login
	 */
	public function set_password(Request $request) {
		if (!$_POST) {
			$password_resets = PasswordResets::whereToken($request->secret);

			if ($password_resets->count()) {
				$password_result = $password_resets->first();

				$datetime1 = new DateTime();
				$datetime2 = new DateTime($password_result->created_at);
				$interval = $datetime1->diff($datetime2);
				$hours = $interval->format('%h');

				if ($hours >= 1) {
					// Delete used token from password_resets table
					$password_resets->delete();

					$this->helper->flash_message('error', trans('messages.login.token_expired')); // Call flash message function
					return redirect('login');
				}

				$data['result'] = User::whereEmail($password_result->email)->first();
				$data['token'] = $request->secret;

				return view('home.set_password', $data);
			} else {
				$this->helper->flash_message('error', trans('messages.login.invalid_token')); // Call flash message function
				return redirect('login');
			}
		} else {
			// Password validation rules
			$rules = array(
				'password' => 'required|min:6|max:30',
				'password_confirmation' => 'required|same:password',
			);

			// Password validation custom Fields name
			$niceNames = array(
				'password' => 'New Password',
				'password_confirmation' => 'Confirm Password',
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				// Delete used token from password_resets table
				$password_resets = PasswordResets::whereToken($request->token)->delete();

				$user = User::find($request->id);

				$user->password = bcrypt($request->password);

				$user->save(); // Update Password in users table

				$this->helper->flash_message('success', trans('messages.login.pwd_changed')); // Call flash message function
				return redirect('login');
			}
		}
	}

	/**
	 * Load Edit profile view file with user dob, timezone and country
	 *
	 * @return edit profile view file
	 */
	public function edit() {
		$data['timezone'] = Timezone::get()->lists('name', 'value');
		$data['country'] = Country::get()->lists('long_name', 'short_name');

		$data['dob'] = explode('-', Auth::user()->user()->dob);

		return view('users.edit', $data);
	}

	/**
	 * Update edit profile page data
	 *
	 * @return redirect     to Edit profile
	 */
	public function update(Request $request, EmailController $email_controller) {
		$user = User::find($request->id);

		$rules = array(
			'email' => 'required|max:255|email|unique:users,email,' . $user->id,
		);
		$messages = array(
			'required' => ':attribute is required.',
		);
		$niceNames = array(
			'email' => 'Email',
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator);
		}

		$new_email = ($user->email != $request->email) ? 'yes' : 'no';

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->gender = $request->gender;
		$user->dob = $request->birthday_year . '-' . $request->birthday_month . '-' . $request->birthday_day;
		$user->email = $request->email;
		$user->live = $request->live;
		$user->about = $request->about;
		$user->school = $request->school;
		$user->work = $request->work;
		$user->timezone = $request->timezone;

		$user->language = $request->language;
		$user->currency_code = $request->currency;

		$user->save(); // Update user profile details

		if ($request->currency) {
			Session::put('currency', $request->currency);
			$symbol = Currency::original_symbol($request->currency);
			Session::put('symbol', $symbol);
		}
		if ($request->language) {
			Session::put('language', $request->language);
			App::setLocale($request->language);
		}

		if ($new_email == 'yes') {
			$email_controller->change_email_confirmation($user);

			$this->helper->flash_message('success', trans('messages.profile.confirm_link_sent', ['email' => $user->email])); // Call flash message function
		} else {
			$this->helper->flash_message('success', trans('messages.profile.profile_updated')); // Call flash message function
		}

		return redirect('users/edit');
	}

	public function update_email(Request $request, EmailController $email_controller) {
		$user = User::find($request->id);

		$rules = array(
			'email' => 'required|max:255|email|unique:users,email,' . $user->id,
		);
		$messages = array(
			'required' => ':attribute is required.',
		);
		$niceNames = array(
			'email' => 'Email',
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator);
		} else {
			$new_email = ($user->email != $request->email) ? 'yes' : 'no';
			$user->email = $request->email;
			$user->save();
			if ($new_email == 'yes') {
				$email_controller->change_email_confirmation($user);
				$this->helper->flash_message('success', trans('messages.profile.confirm_link_sent', ['email' => $user->email])); // Call flash message function
			}
			return redirect()->back();
		}
	}

	public function ajax_update_email(Request $request, EmailController $email_controller) {
		$user = User::find($request->id);

		$rules = array(
			'email' => 'required|max:255|email|unique:users,email,' . $user->id,
		);
		$messages = array(
			'required' => ':attribute is required.',
			'unique' => 'This email is already registered in our system.',
		);
		$niceNames = array(
			'email' => 'Email',
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		$return = array(
			'success' => true,
			'changed' => false
		);

		if ($validator->fails()) {
			$errors = $validator->messages()->getMessages();
			$return['success'] = false;
			$return['error'] = $errors['email'][0];
		} else {
			$new_email = ($user->email != $request->email) ? 'yes' : 'no';
			$user->email = $request->email;
			$user->save();
			if ($new_email == 'yes') {
				$return['changed'] = true;
			}
		}
		return json_encode($return);
	}

	public function update_gender(Request $request, EmailController $email_controller) {
		$user = User::find($request->id);
		$user->gender = $request->gender;
		$user->save();

		$this->helper->flash_message('success', trans('messages.profile.profile_updated'));

		return redirect()->back();
	}

	/**
	 * Confirm email for new email update
	 *
	 * @param array $request Input values
	 * @return redirect to dashboard
	 */
	public function confirm_email(Request $request) {
		$password_resets = PasswordResets::whereToken($request->code);

		if ($password_resets->count() && Auth::user()->user()->email == $password_resets->first()->email) {
			$password_result = $password_resets->first();

			$datetime1 = new DateTime();
			$datetime2 = new DateTime($password_result->created_at);
			$interval = $datetime1->diff($datetime2);
			$hours = $interval->format('%h');

			if ($hours >= 1) {
				// Delete used token from password_resets table
				$password_resets->delete();

				$this->helper->flash_message('error', trans('messages.login.token_expired')); // Call flash message function
				return redirect('login');
			}

			$data['result'] = User::whereEmail($password_result->email)->first();
			$data['token'] = $request->code;

			$user = User::find($data['result']->id);

			$user->status = "Active";

			$user->save();

			$user_verification = UsersVerification::find($data['result']->id);

			$user_verification->email = 'yes';

			$user_verification->save(); // Create a users verification record

			// Delete used token from password_resets table
			$password_resets->delete();

			$this->helper->flash_message('success', trans('messages.profile.email_confirmed')); // Call flash message function
			return redirect('dashboard');
		} else {
			$this->helper->flash_message('error', trans('messages.login.invalid_token')); // Call flash message function
			return redirect('dashboard');
		}
	}

	public function confirm_email_by_otp(Request $request) {
		$password_resets = PasswordResets::whereToken($request->code);

		if ($password_resets->count() && Auth::user()->user()->email == $password_resets->first()->email) {
			$password_result = $password_resets->first();

			$datetime1 = new DateTime();
			$datetime2 = new DateTime($password_result->created_at);
			$interval = $datetime1->diff($datetime2);
			$hours = $interval->format('%h');

			if ($hours >= 1) {
				$password_resets->delete();
				return json_encode( [ 'success' => false, 'error' => trans('messages.login.token_expired') ] );
			}

			$data['result'] = User::whereEmail($password_result->email)->first();
			$data['token'] = $request->code;

			$user = User::find($data['result']->id);
			$user->status = "Active";
			$user->save();

			$user_verification = UsersVerification::find($data['result']->id);
			$user_verification->email = 'yes';
			$user_verification->save();

			$password_resets->delete();

			return json_encode( [ 'success' => true, 'msg' => trans('messages.profile.email_confirmed') ] );
		} else {
			return json_encode( [ 'success' => false, 'error' => trans('messages.login.invalid_token') ] );
		}
	}

	/**
	 * User Profile Page
	 *
	 * @return view profile page
	 */
	public function show(Request $request) {
		$data['result'] = User::find($request->id);

		$data['reviews_from_guests'] = Reviews::where(['user_to' => $request->id, 'review_by' => 'guest']);
		$data['reviews_from_hosts'] = Reviews::where(['user_to' => $request->id, 'review_by' => 'host']);

		$data['reviews_count'] = $data['reviews_from_guests']->count() + $data['reviews_from_hosts']->count();

		$data['wishlists'] = Wishlists::with(['saved_wishlists' => function ($query) {
			$query->with(['rooms']);
		}, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy(0)->orderBy('id', 'desc')->get();

		$data['title'] = $data['result']->first_name . "'s Profile ";

		return view('users.profile', $data);
	}

	/**
	 * User Account Security Page
	 *
	 * @param array $request Input values
	 * @return view security page
	 */
	public function security(Request $request) {
		return view('account.security');
	}

	/**
	 * User Change Password
	 *
	 * @param array $request Input values
	 * @return redirect     to Security page
	 */
	public function change_password(Request $request) {
		// Password validation rules
		$rules = array(
			'old_password' => 'required',
			'new_password' => 'required|min:6|max:30|different:old_password',
			'password_confirmation' => 'required|same:new_password|different:old_password',
		);

		// Password validation custom Fields name
		$niceNames = array(
			'old_password' => 'Old Password',
			'new_password' => 'New Password',
			'password_confirmation' => 'Confirm Password',
		);

		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {
			$user = User::find(Auth::user()->user()->id);

			if (!Hash::check($request->old_password, $user->password)) {
				return back()->withInput()->withErrors(['old_password' => trans('messages.profile.pwd_not_correct')]);
			}

			$user->password = bcrypt($request->new_password);

			$user->save(); // Update password

			$this->helper->flash_message('success', trans('messages.profile.pwd_updated')); // Call flash message function
			return redirect('users/security');
		}
	}

	/**
	 * Add a Payout Method and Load Payout Preferences File
	 *
	 * @param array $request Input values
	 * @return redirect to Payout Preferences page and load payout preferences view file
	 */
	public function payout_preferences(Request $request, EmailController $email_controller) {
		if (!$request->address1) {
			$data['payouts'] = PayoutPreferences::where('user_id', Auth::user()->user()->id)->orderBy('id', 'desc')->get();
			$data['country'] = Country::all()->lists('long_name', 'short_name');
			return view('account/payout_preferences', $data);
		} else {
			$payout = new PayoutPreferences;
			
			$payout->user_id = Auth::user()->user()->id;
			$payout->address1 = $request->address1;
			$payout->address2 = $request->address2;
			$payout->city = $request->city;
			$payout->state = $request->state;
			$payout->postal_code = $request->postal_code;
			$payout->country = $request->country;
			$payout->payout_method = $request->payout_method;
			$payout->paypal_email = $request->paypal_email;
			$payout->currency_code = $request->payout_currency;

			$payout->save();

			$payout_check = PayoutPreferences::where('user_id', Auth::user()->user()->id)->where('default', 'yes')->get();

			if ($payout_check->count() == 0) {
				$payout->default = 'yes';
				$payout->save();
			}

			$email_controller->payout_preferences($payout->id);

			$this->helper->flash_message('success', trans('messages.account.payout_updated')); // Call flash message function
			return redirect('users/payout_preferences/' . Auth::user()->user()->id);
		}
	}

	/**
	 * Delete Payouts Default Payout Method
	 *
	 * @param array $request Input values
	 * @return redirect to Payout Preferences page
	 */
	public function payout_delete(Request $request, EmailController $email_controller) {
		$payout = PayoutPreferences::find($request->id);

		if ($payout->default == 'yes') {
			$this->helper->flash_message('error', trans('messages.account.payout_default')); // Call flash message function
			return redirect('users/payout_preferences/' . Auth::user()->user()->id);
		} else {
			$payout->delete();

			$email_controller->payout_preferences($payout->id, 'delete');

			$this->helper->flash_message('success', trans('messages.account.payout_deleted')); // Call flash message function
			return redirect('users/payout_preferences/' . Auth::user()->user()->id);
		}
	}

	/**
	 * Update Payouts Default Payout Method
	 *
	 * @param array $request Input values
	 * @return redirect to Payout Preferences page
	 */
	public function payout_default(Request $request, EmailController $email_controller) {
		$payout = PayoutPreferences::find($request->id);

		if ($payout->default == 'yes') {
			$this->helper->flash_message('error', trans('messages.account.payout_already_defaulted')); // Call flash message function
			return redirect('users/payout_preferences/' . Auth::user()->user()->id);
		} else {
			$payout_all = PayoutPreferences::where('user_id', Auth::user()->user()->id)->update(['default' => 'no']);

			$payout->default = 'yes';
			$payout->save();

			$email_controller->payout_preferences($payout->id, 'default_update');

			$this->helper->flash_message('success', trans('messages.account.payout_defaulted')); // Call flash message function
			return redirect('users/payout_preferences/' . Auth::user()->user()->id);
		}
	}

	/**
	 * Load Transaction History Page
	 *
	 * @param array $request Input values
	 * @return view Transaction History
	 */
	public function transaction_history(Request $request) {
		$data['lists'] = Rooms::where('user_id', Auth::user()->user()->id)->where('status', 'Listed')->lists('name', 'id');
		$data['payout_methods'] = PayoutPreferences::where('user_id', Auth::user()->user()->id)->lists('paypal_email', 'id');

		$data['from_month'] = [];
		$data['to_month'] = [];
		$data['payout_year'] = [];

		for ($i = 1; $i <= 12; $i++) {
			$data['from_month'][$i] = 'From: ' . date("F", mktime(0, 0, 0, $i, 10));
		}

		for ($i = 1; $i <= 12; $i++) {
			$data['to_month'][$i] = 'To: ' . date("F", mktime(0, 0, 0, $i, 10));
		}

		$user_year = date('Y', strtotime(Auth::user()->user()->created_at));

		for ($i = $user_year; $i <= date('Y'); $i++) {
			$data['payout_year'][$i] = $i;
		}

		return view('account.transaction_history', $data);
	}

	/**
	 * Ajax Transaction History
	 *
	 * @param array $request Input values
	 * @return json Payouts data
	 */
	public function result_transaction_history(Request $request) {
		$data = $request;

		$data = json_decode($data['data']);

		$transaction = $this->transaction_result($data);

		$transaction_result = $transaction->paginate(10)->toJson();

		echo $transaction_result;
	}

	/**
	 * Export Transaction History CSV file
	 *
	 * @param array $request Input values
	 * @return file Exported CSV File
	 */
	public function transaction_history_csv(Request $request) {
		$data = $request;
		$limit = $request->page . '0';
		$offset = ($request->page - 1) . '0';

		$transaction = $this->transaction_result($data);

		$transaction = $transaction->skip($offset)->take($limit)->get();
		$transaction = $transaction->toArray();

		for ($i = 0; $i < count($transaction); $i++) {
			$transaction[$i]['type'] = 'Payout';
			$transaction[$i] = array_only($transaction[$i], ['type', 'date', 'account', 'currency_code', 'amount']);
		}

		Excel::create(strtolower($data->type) . '-history', function ($excel) use ($transaction) {
			$excel->sheet('sheet1', function ($sheet) use ($transaction) {
				$sheet->fromArray($transaction);
			});
		})->export('csv');
	}

	/**
	 * Transactio History Result
	 *
	 * @param array $data Payouts detail
	 * @return array Payouts data
	 */
	public function transaction_result($data) {
		$type = $data->type;
		$payout_method = $data->payout_method;
		$listing = $data->listing;
		$year = @$data->year;
		$start_month = @$data->start_month;
		$end_month = @$data->end_month;

		if ($type == 'completed-transactions') {
			$status = 'Completed';
		} else if ($type == 'future-transactions') {
			$status = 'Future';
		}

		$where['user_id'] = Auth::user()->user()->id;
		$where['status'] = $status;

		if ($payout_method) {
			$where['account'] = PayoutPreferences::find($payout_method)->paypal_email;
		}

		if ($listing) {
			$where['room_id'] = $listing;
		}

		if ($status == 'Completed') {
			$transaction = Payouts::where($where)->whereYear('updated_at', '=', $year)->whereMonth('updated_at', '>=', $start_month)->whereMonth('updated_at', '<=', $end_month);
		} else if ($status == 'Future') {
			$transaction = Payouts::where($where);
		}

		return $transaction;
	}

	/**
	 * Load Reviews for both Guest and Host with Previous reviews
	 *
	 * @param array $request Input values
	 * @return view User Reviews file
	 */
	public function reviews(Request $request) {
		$data['reviews_about_you'] = Reviews::where('user_to', Auth::user()->user()->id)->orderBy('id', 'desc')->get();
		$data['reviews_by_you'] = Reviews::where('user_from', Auth::user()->user()->id)->orderBy('id', 'desc')->get();

		$data['reviews_to_write'] = Reservation::with(['reviews'])->whereRaw('DATEDIFF(now(),checkout) <= 14')->whereRaw('DATEDIFF(now(),checkout) >= 1')->where(['status' => 'Accepted'])->where(function ($query) {
			return $query->where('user_id', Auth::user()->user()->id)->orWhere('host_id', Auth::user()->user()->id);
		})->get();

		$data['expired_reviews'] = Reservation::with(['reviews'])->whereRaw('DATEDIFF(now(),checkout) > 14')->where(function ($query) {
			return $query->where('user_id', Auth::user()->user()->id)->orWhere('host_id', Auth::user()->user()->id);
		})->get();

		$data['reviews_to_write_count'] = 0;

		for ($i = 0; $i < $data['reviews_to_write']->count(); $i++) {
			if ($data['reviews_to_write'][$i]->review_days > 0 && $data['reviews_to_write'][$i]->reviews->count() < 2) {
				if ($data['reviews_to_write'][$i]->reviews->count() == 0) {
					$data['reviews_to_write_count'] += 1;
				}

				for ($j = 0; $j < $data['reviews_to_write'][$i]->reviews->count(); $j++) {
					if (@$data['reviews_to_write'][$i]->reviews[$j]->user_from != Auth::user()->user()->id) {
						$data['reviews_to_write_count'] += 1;
					}

				}
			}
		}

		$data['expired_reviews_count'] = 0;

		for ($i = 0; $i < $data['expired_reviews']->count(); $i++) {
			if ($data['expired_reviews'][$i]->review_days <= 0 && $data['expired_reviews'][$i]->reviews->count() < 2) {
				if ($data['expired_reviews'][$i]->reviews->count() == 0) {
					$data['expired_reviews_count'] += 1;
				}

				for ($j = 0; $j < $data['expired_reviews'][$i]->reviews->count(); $j++) {
					if (@$data['expired_reviews'][$i]->reviews->user_from != Auth::user()->user()->id) {
						$data['expired_reviews_count'] += 1;
					}

				}
			}
		}

		return view('users.reviews', $data);
	}

	/**
	 * Edit Reviews for both Guest and Host
	 *
	 * @param array $request Input values
	 * @return json success and review_id
	 */
	public function reviews_edit(Request $request) {
		$data['result'] = $reservation_details = Reservation::find($request->id);

		if (Auth::user()->user()->id == $reservation_details->user_id) {
			$reviews_check = Reviews::where(['reservation_id' => $request->id, 'review_by' => 'guest'])->get();
			$data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
		} else {
			$reviews_check = Reviews::where(['reservation_id' => $request->id, 'review_by' => 'host'])->get();
			$data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
		}

		if (!$request->data) {
			if ($reservation_details->user_id == Auth::user()->user()->id) {
				return view('users.reviews_edit_guest', $data);
			} else if ($reservation_details->host_id == Auth::user()->user()->id) {
				return view('users.reviews_edit_host', $data);
			}

		} else {
			$data = $request;
			$data = json_decode($data['data']);

			if ($data->review_id == '') {
				$reviews = new Reviews;
			} else {
				$reviews = Reviews::find($data->review_id);
			}

			$reviews->reservation_id = $reservation_details->id;
			$reviews->room_id = $reservation_details->room_id;

			if ($reservation_details->user_id == Auth::user()->user()->id) {
				$reviews->user_from = $reservation_details->user_id;
				$reviews->user_to = $reservation_details->host_id;
				$reviews->review_by = 'guest';
			} else if ($reservation_details->host_id == Auth::user()->user()->id) {
				$reviews->user_from = $reservation_details->host_id;
				$reviews->user_to = $reservation_details->user_id;
				$reviews->review_by = 'host';
			}

			foreach ($data as $key => $value) {
				if ($key != 'section' && $key != 'review_id') {
					$reviews->$key = $value;
				}
			}
			$reviews->save();

			return json_encode(['success' => true, 'review_id' => $reviews->id]);
		}
	}

	public function add_place_reviews(Request $request) {

		$mode = $request->mode;
		$user_id = Auth::user()->user()->id;

		if ($_POST) {

			//Place Reviews Form Validation Rules
			$rules = array(
				'place_id' => 'required',
				'place' => 'required',
				'place_comments' => 'required',
			);

			//Place Reviews Form Validation Names
			$niceNames = array(
				'place_id' => 'Place',
				'place' => 'Place Rating',
				'place_comments' => 'Place Comments',
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$place_review_find = PlaceReviews::where(['user_from' => $user_id, 'place_id' => $request->place_id])->first();

				if ($place_review_find == '') {
					$place_review = new PlaceReviews;
					$place_review->created_at = date('Y-m-d H:i:s');
					$place_review->updated_at = date('Y-m-d H:i:s');
				} else {
					$place_review = PlaceReviews::find($place_review_find->id);
					$place_review->updated_at = date('Y-m-d H:i:s');
				}

				$place_review->user_from = $user_id;
				$place_review->place_id = $request->place_id;
				$place_review->place = $request->place;
				$place_review->place_comments = $request->place_comments;

				$place_review->save();

				$this->helper->flash_message('success', trans('messages.new.place_review_added')); // Call flash message function
				if ($mode == 'reviews') {
					$reviews = Reviews::find($request->id);
					$reviews->place_id = $request->place_id;
					$reviews->save();
					return redirect('users/reviews');
				} elseif ($mode == 'place') {
					return redirect('add_place_reviews/place/' . $request->place_id);
				}
			}
		} else {

			if ($mode == 'reviews') {
				$reviews = Reviews::find($request->id);
				if ($reviews->place_id != '') {
					return redirect('users/reviews');
				}
				$room_address_details = RoomsAddress::whereRoomId($reviews->room_id)->first();
				$near_by_places = Places::select(DB::raw('*, ( 3959 * acos( cos( radians(' . $room_address_details->latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $room_address_details->longitude . ') ) + sin( radians(' . $room_address_details->latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
					->having('distance', '<=', 2.2);
			} elseif ($mode == 'place') {
				$place_id = $request->id;
				$near_by_places = Places::where(['id' => $place_id]);
				$data['review'] = PlaceReviews::where(['user_from' => $user_id, 'place_id' => $place_id])->first();
			}

			$near_by_places = $near_by_places->get()->lists('name', 'id');

			$data['near_by_places'] = $near_by_places;
			$data['user_id'] = $user_id;

			$data['title'] = trans('messages.new.add_place_review');

			return view('users.add_place_reviews', $data);
		}
	}
	/**
	 * Load User Media page
	 *
	 * @return view User Media file
	 */
	public function media() {
		$data['result'] = User::find(Auth::user()->user()->id);

		return view('users.media', $data);
	}

	/**
	 * User Profile Image Upload
	 *
	 * @param array $request Input values
	 * @return redirect to User Media Page
	 */
	public function image_upload(Request $request) {
		$image = $request->file('profile_pic');

		if ($image) {
			$extension = $image->getClientOriginalExtension();
			$filename = 'profile_pic_' . time() . '.' . $extension;
			$imageRealPath = $image->getRealPath();

			$img = Image::make($imageRealPath);

			$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $request->user_id;

			if (!file_exists($path)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $request->user_id, 0777, true);
			}

			$success = $img->save('images/users/' . $request->user_id . '/' . $filename);

			if (!$success) {
				$this->helper->flash_message('error', trans('messages.profile.cannot_upload')); // Call flash message function
				return back();
			}

			$user_pic = ProfilePicture::find($request->user_id);

			$user_pic->user_id = $request->user_id;
			$user_pic->src = $filename;
			$user_pic->photo_source = 'Local';

			$user_pic->save(); // Update a profile picture record

			$this->helper->flash_message('success', trans('messages.profile.picture_uploaded')); // Call flash message function
			return redirect('users/edit/media');
		}
	}

	public function ajax_upload_profile_image(Request $request) {
		// print_r($request);
		$image = $request->file('profile_pic');
		if($image) {
			$extension = $image->getClientOriginalExtension();
			$filename = 'profile_pic_' . time() . '.' . $extension;
			$imageRealPath = $image->getRealPath();

			$img = Image::make($imageRealPath);

			$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $request->user_id;

			if (!file_exists($path)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $request->user_id, 0777, true);
			}

			$success = $img->save('images/users/' . $request->user_id . '/' . $filename);

			if (!$success) {
				return json_encode(['success' => false, 'error' => trans('messages.profile.cannot_upload')]);
			}

			$user_pic = ProfilePicture::find($request->user_id);

			$user_pic->user_id = $request->user_id;
			$user_pic->src = $filename;
			$user_pic->photo_source = 'Local';

			$user_pic->save(); // Update a profile picture record

			return json_encode(['success' => true, 'message' => trans('messages.profile.picture_uploaded'), 'src' => Auth::user()->user()->profile_picture->src]);
		}
	}

	/**
	 * Send New Confirmation Email
	 *
	 * @param array $request Input values
	 * @param array $email_controller Instance of EmailController
	 * @return redirect to Dashboard
	 */
	public function request_new_confirm_email(Request $request, EmailController $email_controller) {
		$user = User::find(Auth::user()->user()->id);

		$email_controller->new_email_confirmation($user);

		$this->helper->flash_message('success', trans('messages.profile.new_confirm_link_sent', ['email' => $user->email])); // Call flash message function

		if ($request->redirect == 'verification') {
			return redirect('users/edit_verification');
		} else {
			// return redirect('dashboard');
			return redirect()->back();
		}

	}

	public function request_email_otp( Request $request, EmailController $email_controller ) {
		$user = User::find(Auth::user()->user()->id);
		$email_controller->email_otp($user);
		return json_encode( [ 'success' => true ] );
	}

	public function verification(Request $request) {
		$data['fb_url'] = $this->fb->getUrlConnect();
		$user_id = Auth::user()->user()->id;
		$data['phone_info'] = $phone_info = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();

		return view('users.verification', $data);
	}

	public function facebookConnect(Request $request) {
		if ($request['error']) {
			$this->helper->flash_message('danger', $request['error_description']); // Call flash message function
			return redirect('users/edit_verification'); // Redirect to login page
		}

		$this->fb->generateSessionFromRedirect(); // Generate Access Token Session After Redirect from Facebook

		$response = $this->fb->getData(); // Get Facebook Response

		$userNode = $response->getGraphUser(); // Get Authenticated User Data

		$fb_id = $userNode->getId();

		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->facebook = 'yes';
		$verification->fb_id = $fb_id;
		$verification->facebook_token = $_SESSION['facebook_access_token'];

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social' => 'Facebook'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	public function facebookDisconnect(Request $request) {
		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->facebook = 'no';
		$verification->fb_id = '';

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.disconnected_successfully', ['social' => 'Facebook'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	/**
	 * Google User redirect to Google Authentication page
	 *
	 * @return redirect     to Google page
	 */
	public function googleLoginVerification() {
		Session::put('verification', 'yes');
		return Socialite::with('google')->redirect();
	}

	public function googleConnect(Request $request) {
		$google_id = $request->id;

		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->google = 'yes';
		$verification->google_id = $google_id;

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social' => 'Google'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	public function googleDisconnect(Request $request) {
		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->google = 'no';
		$verification->google_id = '';

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.disconnected_successfully', ['social' => 'Google'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	/**
	 * LinkedIn User redirect to LinkedIn Authentication page
	 *
	 * @return redirect     to LinkedIn page
	 */
	public function linkedinLoginVerification() {
		return Socialite::driver('linkedin')->redirect();
	}

	public function linkedinConnect(Request $request) {
		$userNode = Socialite::driver('linkedin')->user();

		$linkedin_id = $userNode->getId();

		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->linkedin = 'yes';
		$verification->linkedin_id = $linkedin_id;

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social' => 'LinkedIn'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	public function linkedinDisconnect(Request $request) {
		$verification = UsersVerification::find(Auth::user()->user()->id);

		$verification->linkedin = 'no';
		$verification->linkedin_id = '';

		$verification->save();

		$this->helper->flash_message('success', trans('messages.profile.disconnected_successfully', ['social' => 'LinkedIn'])); // Call flash message function
		return redirect('users/edit_verification');
	}

	public function get_rand_string($length) {
		$code = '';
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, 9);
			if ($rand != '') {
				$code .= $rand;
			} else {
				$i--;
			}
		}
		return $code;
	}

	/**
	 * delete phone number
	 *
	 * @param array $request Input values
	 * @return redirect to dashboard
	 */
	public function delete_phone(Request $request) {

		$user_id = Auth::user()->user()->id;
		$phone_id = $request->get('phone_id');
		$check = UsersPhoneNumber::where(['user_id' => $user_id, 'id' => $phone_id])->delete();
		if ($check == 1) {
			$this->helper->flash_message('success', trans('messages.profile.phone_number_deleted')); // Call flash message function
			return redirect('users/phone_list');
		} else {
			$this->helper->flash_message('error', trans('messages.profile.something_went_wrong')); // Call flash message function
			return redirect('users/phone_list');
		}

	}

	/**
	 * delete reference
	 *
	 * @param array $request Input values
	 * @return redirect to dashboard
	 */
	public function delete_reference(Request $request) {

		$user_id = Auth::user()->user()->id;
		$ref_id = $request->get('ref_id');
		$check = References::where(['user_id' => $user_id, 'id' => $ref_id])->delete();
		if ($check == 1) {
			$this->helper->flash_message('success', trans('messages.profile.reference_deleted')); // Call flash message function
			return redirect('users/references');
		} else {
			$this->helper->flash_message('error', trans('messages.profile.something_went_wrong')); // Call flash message function
			return redirect('users/references');
		}

	}

	/**
	 * add reference
	 *
	 * @param array $request Input values
	 * @return redirect to dashboard
	 */
	public function add_reference(Request $request) {

		$user_id = Auth::user()->user()->id;

		$post_data['user_id'] = $user_id;
		$post_data['name'] = $request['name'];
		$post_data['email1'] = $request['email1'];
		$post_data['phone1'] = $request['phone1'];
		$post_data['email2'] = $request['email2'];
		$post_data['phone2'] = $request['phone2'];
		$post_data['relationship'] = $request['relationship'];
		$post_data['time_to_call'] = $request['time_to_call'];

		$last_id = References::insertGetId($post_data);

		if ($last_id) {
			$this->helper->flash_message('success', trans('messages.profile.reference_added')); // Call flash message function
			return redirect('users/references');
		} else {
			$this->helper->flash_message('error', trans('messages.profile.something_went_wrong')); // Call flash message function
			return redirect('users/references');
		}

	}
}
