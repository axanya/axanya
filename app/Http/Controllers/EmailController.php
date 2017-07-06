<?php

/**
 * Email Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Email
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PasswordResets;
use App\Models\PayoutPreferences;
use App\Models\ReferralSettings;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\User;
use Auth;
use Config;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller {
	/**
	 * Send Welcome Mail to Users with Confirmation Link
	 *
	 * @param array $user  User Details
	 * @return true
	 */
	public function welcome_email_confirmation($user) {
		$data['first_name'] = $user->first_name;
		$data['email'] = $user->email;
		$data['token'] = str_random(100); // Generate random string values - limit 100
		$data['type'] = 'welcome';
		$data['url'] = url() . '/';

		$password_resets = new PasswordResets;

		$password_resets->email = $user->email;
		$password_resets->token = $data['token'];
		$password_resets->created_at = date('Y-m-d H:i:s');

		$password_resets->save(); // Insert a generated token and email in password_resets table

		// Send Forgot password email to give user email
		Mail::send('emails.email_confirm', $data, function ($message) use ($data) {
			$message->to($data['email'], $data['first_name'])->subject('Please confirm your e-mail address');
		});

		return true;
	}

	/**
	 * Send Forgot Password Mail with Confirmation Link
	 *
	 * @param array $user  User Details
	 * @return true
	 */
	public function forgot_password($user) {
		$data['first_name'] = $user->first_name;

		$data['token'] = str_random(100); // Generate random string values - limit 100
		$data['url'] = url() . '/';

		$password_resets = new PasswordResets;

		$password_resets->email = $user->email;
		$password_resets->token = $data['token'];
		$password_resets->created_at = date('Y-m-d H:i:s');

		$password_resets->save(); // Insert a generated token and email in password_resets table

		// Send Forgot password email to give user email
		Mail::send('emails.forgot_password', $data, function ($message) use ($user) {
			$message->to($user->email, $user->first_name)->subject('Reset your Password');
		});

		return true;
	}

	/**
	 * Send Email Change Mail with Confirmation Link
	 *
	 * @param array $user  User Details
	 * @return true
	 */
	public function change_email_confirmation($user) {
		$data['first_name'] = $user->first_name;
		$data['token'] = str_random(100); // Generate random string values - limit 100
		$data['type'] = 'change';
		$data['url'] = url() . '/';

		$password_resets = new PasswordResets;

		$password_resets->email = $user->email;
		$password_resets->token = $data['token'];
		$password_resets->created_at = date('Y-m-d H:i:s');

		$password_resets->save(); // Insert a generated token and email in password_resets table

		// Send Forgot password email to give user email
		Mail::send('emails.email_confirm', $data, function ($message) use ($user) {
			$message->to($user->email, $user->first_name)->subject('Please confirm your e-mail address');
		});

		return true;
	}

	/**
	 * Send New Email Change Mail with Confirmation Link
	 *
	 * @param array $user  User Details
	 * @return true
	 */
	public function new_email_confirmation($user) {
		$data['first_name'] = $user->first_name;
		$data['token'] = str_random(100); // Generate random string values - limit 100
		$data['type'] = 'confirm';
		$data['url'] = url() . '/';

		$password_resets = new PasswordResets;

		$password_resets->email = $user->email;
		$password_resets->token = $data['token'];
		$password_resets->created_at = date('Y-m-d H:i:s');

		$password_resets->save(); // Insert a generated token and email in password_resets table

		// Send Forgot password email to give user email
		Mail::send('emails.email_confirm', $data, function ($message) use ($user) {
			$message->to($user->email, $user->first_name)->subject('Please confirm your e-mail address');
		});

		return true;
	}

	public function email_otp( $user ) {
		$data['first_name'] = $user->first_name;
		$data['otp'] = $this->generate_otp(6);
		$data['type'] = 'otp';
		$data['url'] = url() . '/';

		$password_resets = new PasswordResets;
		$password_resets->email = $user->email;
		$password_resets->token = $data['otp'];
		$password_resets->created_at = date('Y-m-d H:i:s');
		$password_resets->save();

		Mail::send('emails.email_otp', $data, function ($message) use ($user) {
			$message->to($user->email, $user->first_name)->subject('Please confirm your e-mail address');
		});

		return true;
	}

	public function generate_otp( $length = 8 ) {
		$chars = '0123456789';
		$otp = '';
		for($i = 1; $i <= $length; $i++) {
			$r = $chars{mt_rand( 0, strlen( $chars ) - 1 )};
			$otp .= $r;
		}
		return $otp;
	}

	/**
	 * Send Inquiry Mail to Host
	 *
	 * @param array $reservation_id Contact Request Details
	 * @return true
	 */
	public function inquiry($reservation_id, $question) {
		$data['result'] = Reservation::find($reservation_id);
		$data['question'] = $question;
		$user = $data['result']->host_users;
		$data['url'] = url() . '/';

		$data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'rooms', 'host_users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'currency']);

		$data['result'] = $data['result']->first()->toArray();

		Mail::send('emails.inquiry', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("Inquiry at " . $data['result']['rooms']['name'] . " for " . $data['result']['dates_subject']);
		});
		return true;
	}

	/**
	 * Send Booking Mail to Host
	 *
	 * @param array $reservation_id Request Details
	 * @return true
	 */
	public function booking($reservation_id) {
		$data['result'] = Reservation::find($reservation_id);
		$user = $data['result']->host_users;
		$data['hide_header'] = true;
		$data['url'] = url() . '/';

		$data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'rooms', 'host_users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'currency', 'messages']);

		$data['result'] = $data['result']->first()->toArray();

		Mail::send('emails.booking', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("Booking inquiry for " . $data['result']['rooms']['name'] . " for " . $data['result']['dates_subject']);
		});
		return true;
	}

	/**
	 * Send itinerary Mail to Host
	 *
	 * @param string $reservation_id Reservation Code
	 * @param string $email Friend Email
	 * @return true
	 */
	public function itinerary($code, $email) {
		$data['result'] = Reservation::where('code', $code)->first();
		$user = $data['result']->host_users;
		$data['hide_header'] = true;
		$data['email'] = $email;
		$data['url'] = url() . '/';
		$data['map_key'] = MAP_KEY;

		$data['result'] = Reservation::where('reservation.id', $data['result']->id)->with(['users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'rooms' => function ($query) {
			$query->with('rooms_address');
		}, 'host_users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'currency']);

		$data['result'] = $data['result']->first()->toArray();

		Mail::send('emails.itinerary', $data, function ($message) use ($user, $data) {
			$message->to($data['email'], '')->subject("Reservation Itinerary from " . $data['result']['users']['full_name']);
		});
		return true;
	}

	/**
	 * Send preapproval Mail to Host
	 *
	 * @param array $reservation_id Reservation Id
	 * @param string $preapproval_message Message from Host when pre-approving
	 * @param type for Checking Pre-approval or Special-Offer
	 * @return true
	 */
	public function preapproval($reservation_id, $preapproval_message, $type = 'pre-approval') {
		$data['result'] = Reservation::find($reservation_id);
		$user = $data['result']->users;
		$data['first_name'] = $user->first_name;
		$data['preapproval_message'] = $preapproval_message;
		$data['type'] = $type;
		$data['url'] = url() . '/';

		$data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'rooms' => function ($query) {
			$query->with('rooms_address');
		}, 'host_users' => function ($query) {
			$query->with('profile_picture')->with('users_verification')->with('reviews');
		}, 'currency', 'special_offer' => function ($query) {
			$query->orderby('special_offer.id', 'desc')->limit(1)->with('rooms');
		}]);

		$data['result'] = $data['result']->first()->toArray();

		if ($type == 'pre-approval') {
			$subject = $data['result']['host_users']['first_name'] . " invited you to book " . $data['result']['special_offer']['rooms']['name'] . " for " . $data['result']['special_offer']['dates_subject'];
		} else if ($type == 'special_offer') {
			$subject = $data['result']['host_users']['first_name'] . " sent a Special Offer for " . $data['result']['special_offer']['rooms']['name'] . " for " . $data['result']['special_offer']['dates_subject'];
		}

		Mail::send('emails.preapproval', $data, function ($message) use ($user, $data, $subject) {
			$message->to($user->email, $user->first_name)->subject($subject);
		});
		return true;
	}

	/**
	 * Send Listed Mail to Host
	 *
	 * @param array $room_id Room Details
	 * @return true
	 */
	public function listed($room_id) {
		$result = Rooms::find($room_id);
		$user = $result->users;
		$data['first_name'] = $user->first_name;
		$data['room_name'] = $result->name;
		$data['created_time'] = $result->created_time;
		$data['room_id'] = $result->id;
		$data['url'] = url() . '/';

		Mail::send('emails.listed', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("Your space has been listed on " . SITE_NAME);
		});
		return true;
	}

	/**
	 * Send Unlisted Mail to Host
	 *
	 * @param array $room_id Room Details
	 * @return true
	 */
	public function unlisted($room_id) {
		$result = Rooms::find($room_id);
		$user = $result->users;
		$data['first_name'] = $user->first_name;
		$data['created_time'] = $result->created_time;
		$data['room_id'] = $result->id;
		$data['url'] = url() . '/';

		Mail::send('emails.unlisted', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("A listing has been deactivated from your " . SITE_NAME . " account");
		});
		return true;
	}

	/**
	 * Send Updated Payout Information Mail to Host
	 *
	 * @param array $payout_preference_id Payout Preference Details
	 * @return true
	 */
	public function payout_preferences($payout_preference_id, $type = 'update') {
		if ($type != 'delete') {
			$result = PayoutPreferences::find($payout_preference_id);
			$user = $result->users;
			$data['first_name'] = $user->first_name;
			$data['updated_time'] = $result->updated_time;
			$data['updated_date'] = $result->updated_date;
		} else {
			$user = Auth::user()->user();
			$data['first_name'] = $user->first_name;
			$new_str = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone(Config::get('app.timezone')));
			$new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));
			$data['deleted_time'] = $new_str->format('d M') . ' at ' . $new_str->format('H:i');
		}
		$data['type'] = $type;
		$data['url'] = url() . '/';

		if ($type == 'update') {
			$subject = "Your " . SITE_NAME . " payout information has been updated";
		} else if ($type == 'delete') {
			$subject = "Your " . SITE_NAME . " payout information has been deleted";
		} else if ($type == 'default_update') {
			$subject = "Your Default Payout Information Has Been Changed";
		}

		Mail::send('emails.payout_preferences', $data, function ($message) use ($user, $data, $subject) {
			$message->to($user->email, $user->first_name)->subject($subject);
		});
		return true;
	}

	/**
	 * Send Need Payout Information Mail to Host/Guest
	 *
	 * @param array $reservation_id Reservation Details
	 * @return true
	 */
	public function need_payout_info($reservation_id, $type) {
		$result = Reservation::find($reservation_id);
		$data['type'] = $type;

		if ($type == 'guest') {
			$user = $result->users;
			$data['payout_amount'] = $result->admin_guest_payout;
		} else {
			$user = $result->host_users;
			$data['payout_amount'] = $result->admin_host_payout;
		}

		$data['currency_symbol'] = $result->currency->symbol;
		$data['first_name'] = $user->first_name;
		$data['user_id'] = $user->id;
		$data['url'] = url() . '/';

		Mail::send('emails.need_payout_info', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("Information Needed: It's time to get paid!");
		});
		return true;
	}

	/**
	 * Send Need Payout Sent Mail to Host/Guest
	 *
	 * @param array $reservation_id Reservation Details
	 * @return true
	 */
	public function payout_sent($reservation_id, $type) {
		$data['result'] = Reservation::find($reservation_id);
		$data['type'] = $type;

		if ($type == 'guest') {
			$user = $data['result']->users;
			$data['full_name'] = $data['result']->host_users->full_name;
			$data['payout_amount'] = $data['result']->admin_guest_payout;
		} else {
			$user = $data['result']->host_users;
			$data['full_name'] = $data['result']->users->full_name;
			$data['payout_amount'] = $data['result']->admin_host_payout;
		}

		$data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['rooms', 'currency'])->first()->toArray();
		$data['first_name'] = $user->first_name;
		$data['url'] = url() . '/';

		Mail::send('emails.payout_sent', $data, function ($message) use ($user, $data) {
			$message->to($user->email, $user->first_name)->subject("Payout of " . html_entity_decode($data['result']['currency']['symbol'], ENT_NOQUOTES, 'UTF-8') . $data['payout_amount'] . " sent");
		});
		return true;
	}

	/**
	 * Referral Email Share
	 *
	 * @param array $emails Friend Emails
	 * @return true
	 */
	public function referral_email_share($emails) {
		$user_id = Auth::user()->user()->id;

		$data['result'] = $user = User::with(['profile_picture'])->whereId($user_id)->first()->toArray();

		$data['travel_credit'] = ReferralSettings::value(4);
		$data['symbol'] = Currency::first()->symbol;

		$data['url'] = url() . '/';

		$emails = explode(',', $emails);

		foreach ($emails as $email) {
			$email = trim($email);
			Mail::send('emails.referral_email_share', $data, function ($message) use ($user, $data, $email) {
				$message->to($email)->subject($user['full_name'] . " invited you to " . SITE_NAME);
			});
		}
		return true;
	}
}
