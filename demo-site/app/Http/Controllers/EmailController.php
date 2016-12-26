<?php

/**
 * Email Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Email
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use Config;
use Auth;
use DateTime;
use DateTimeZone;
use App\Models\PasswordResets;
use App\Models\User;
use App\Models\Rooms;
use App\Models\Reservation;
use App\Models\SiteSettings;
use App\Models\PayoutPreferences;
use App\Models\ReferralSettings;
use App\Models\Currency;
use App\Models\Reviews;
use App;

class EmailController extends Controller
{
    /**
     * Send Welcome Mail to Users with Confirmation Link
     *
     * @param array $user  User Details
     * @return true
     */
    public function welcome_email_confirmation($user)
    {
        $data['first_name'] = $user->first_name;
        $data['email'] = $user->email;
        $data['token'] = str_random(100); // Generate random string values - limit 100
        $data['type'] = 'welcome';
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');

        $password_resets->save(); // Insert a generated token and email in password_resets table
        
        // Send Forgot password email to give user email
        Mail::queue('emails.email_confirm', $data, function ($message) use ($data)
        {
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
    public function forgot_password($user)
    {
        $data['first_name'] = $user->first_name;

        $data['token'] = str_random(100); // Generate random string values - limit 100
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');
        
        $password_resets->save(); // Insert a generated token and email in password_resets table

        // Send Forgot password email to give user email
        Mail::queue('emails.forgot_password', $data, function ($message) use ($user)
        {
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
    public function change_email_confirmation($user)
    {
        $data['first_name'] = $user->first_name;
        $data['token'] = str_random(100); // Generate random string values - limit 100
        $data['type'] = 'change';
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');

        $password_resets->save(); // Insert a generated token and email in password_resets table

        // Send Forgot password email to give user email
        Mail::queue('emails.email_confirm', $data, function ($message) use ($user)
        {
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
    public function new_email_confirmation($user)
    {
        $data['first_name'] = $user->first_name;
        $data['token'] = str_random(100); // Generate random string values - limit 100
        $data['type'] = 'confirm';
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $password_resets = new PasswordResets;

        $password_resets->email      = $user->email;
        $password_resets->token      = $data['token'];
        $password_resets->created_at = date('Y-m-d H:i:s');

        $password_resets->save(); // Insert a generated token and email in password_resets table

        // Send Forgot password email to give user email
        Mail::queue('emails.email_confirm', $data, function ($message) use ($user)
        {
            $message->to($user->email, $user->first_name)->subject('Please confirm your e-mail address');
        });

        return true;
    }

    /**
     * Send Inquiry Mail to Host
     *
     * @param array $reservation_id Contact Request Details
     * @return true
     */
    public function inquiry($reservation_id, $question)
    {
        $data['result'] = Reservation::find($reservation_id);
        $data['question'] = $question;
        $user = $data['result']->host_users;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'rooms', 'host_users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'currency']);

        $data['result'] = $data['result']->value('toArray()');

        Mail::queue('emails.inquiry', $data, function ($message) use ($user, $data)
        {
            $message->to($user->email, $user->first_name)->subject("Inquiry at ".$data['result']['rooms']['name']." for ".$data['result']['dates_subject']);
        });
        return true;
    }

    /**
     * Send Booking Mail to Host
     *
     * @param array $reservation_id Request Details
     * @return true
     */
    public function booking($reservation_id)
    {
        $data['result'] = Reservation::find($reservation_id);
        $user = $data['result']->host_users;
        $data['hide_header'] = true;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'rooms', 'host_users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'currency', 'messages']);

        $data['result'] = $data['result']->value('toArray()');

        Mail::queue('emails.booking', $data, function ($message) use ($user, $data)
        {
            $message->to($user->email, $user->first_name)->subject("Booking inquiry for ".$data['result']['rooms']['name']." for ".$data['result']['dates_subject']);
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
    public function itinerary($code, $email)
    {
        $data['result'] = Reservation::where('code', $code)->first();
        $user = $data['result']->host_users;
        $data['hide_header'] = true;
        $data['email'] = $email;
        $data['url'] = url().'/';
        $data['map_key'] = MAP_KEY;
        $data['locale'] = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $data['result']->id)->with(['users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'rooms' => function($query) {
                $query->with('rooms_address');
            }, 'host_users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'currency']);

        $data['result'] = $data['result']->value('toArray()');

        Mail::queue('emails.itinerary', $data, function ($message) use ($user, $data)
        {
            $message->to($data['email'], '')->subject("Reservation Itinerary from ".$data['result']['users']['full_name']);
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
    public function preapproval($reservation_id, $preapproval_message, $type = 'pre-approval')
    {
        $data['result']              = Reservation::find($reservation_id);
        $user                        = $data['result']->users;
        $data['first_name']          = $user->first_name;
        $data['preapproval_message'] = $preapproval_message;
        $data['type']                = $type;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $reservation_id)->with(['users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'rooms' => function($query) {
                $query->with('rooms_address');
            }, 'host_users' => function($query) {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            }, 'currency', 'special_offer' => function($query) {
                $query->orderby('special_offer.id','desc')->limit(1)->with('rooms');
            }]);

        $data['result'] = $data['result']->value('toArray()');
        
        if($type == 'pre-approval') {
            $subject = $data['result']['host_users']['first_name']." invited you to book ".$data['result']['special_offer']['rooms']['name']." for ".$data['result']['special_offer']['dates_subject'];
        }
        else if($type == 'special_offer') {
            $subject = $data['result']['host_users']['first_name']." sent a Special Offer for ".$data['result']['special_offer']['rooms']['name']." for ".$data['result']['special_offer']['dates_subject'];
        }

        Mail::queue('emails.preapproval', $data, function ($message) use ($user, $data, $subject)
        {
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
    public function listed($room_id)
    {
        $result               = Rooms::find($room_id);
        $user                 = $result->users;
        $data['first_name']   = $user->first_name;
        $data['room_name']    = $result->name;
        $data['created_time'] = $result->created_time;
        $data['room_id']      = $result->id;
        $data['url']          = url().'/';
        $data['locale'] = App::getLocale();
        Mail::queue('emails.listed', $data, function ($message) use ($user, $data)
        {
            $message->to($user->email, $user->first_name)->subject("Your space has been listed on ".SITE_NAME);
        });
        return true;
    }

    /**
     * Send Unlisted Mail to Host
     *
     * @param array $room_id Room Details
     * @return true
     */
    public function unlisted($room_id)
    {
        $result = Rooms::find($room_id);
        $user = $result->users;
        $data['first_name'] = $user->first_name;
        $data['created_time'] = $result->created_time;
        $data['room_id'] = $result->id;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        Mail::queue('emails.unlisted', $data, function ($message) use ($user, $data)
        {
            $message->to($user->email, $user->first_name)->subject("A listing has been deactivated from your ".SITE_NAME." account");
        });
        return true;
    }

    /**
     * Send Updated Payout Information Mail to Host
     *
     * @param array $payout_preference_id Payout Preference Details
     * @return true
     */
    public function payout_preferences($payout_preference_id, $type = 'update')
    {
        if($type != 'delete') {
            $result = PayoutPreferences::find($payout_preference_id);
            $user = $result->users;
            $data['first_name'] = $user->first_name;
            $data['updated_time'] = $result->updated_time;
            $data['updated_date'] = $result->updated_date;
        }
        else {
            $user = Auth::user()->user();
            $data['first_name'] = $user->first_name;
            $new_str = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone(Config::get('app.timezone')));
            $new_str->setTimeZone(new DateTimeZone(Auth::user()->user()->timezone));
            $data['deleted_time'] = $new_str->format('d M').' at '.$new_str->format('H:i');
        }
        $data['type'] = $type;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        if($type == 'update')
            $subject = "Your ".SITE_NAME." payout information has been updated";
        else if($type == 'delete')
            $subject = "Your ".SITE_NAME." payout information has been deleted";
        else if($type == 'default_update')
            $subject = "Your Default Payout Information Has Been Changed";

        Mail::queue('emails.payout_preferences', $data, function ($message) use ($user, $data, $subject)
        {
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
    public function need_payout_info($reservation_id, $type)
    {
        $result       = Reservation::find($reservation_id);
        $data['type'] = $type;
        
        if($type == 'guest') {
            $user = $result->users;
            $data['payout_amount'] = $result->admin_guest_payout;
        }
        else {
            $user = $result->host_users;
            $data['payout_amount'] = $result->admin_host_payout;
        }

        $data['currency_symbol'] = $result->currency->symbol;
        $data['first_name']      = $user->first_name;
        $data['user_id']         = $user->id;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();
        Mail::queue('emails.need_payout_info', $data, function ($message) use ($user, $data)
        {
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
    public function payout_sent($reservation_id, $type)
    {
        $data['result'] = Reservation::find($reservation_id);
        $data['type'] = $type;
        
        if($type == 'guest') {
            $user = $data['result']->users;
            $data['full_name'] = $data['result']->host_users->full_name;
            $data['payout_amount'] = $data['result']->admin_guest_payout;
        }
        else{
            $user = $data['result']->host_users;
            $data['full_name'] = $data['result']->users->full_name;
            $data['payout_amount'] = $data['result']->admin_host_payout;
        }

        $data['result'] = Reservation::where('reservation.id',$reservation_id)->with(['rooms', 'currency'])->value('toArray()');
        $data['first_name'] = $user->first_name;
        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        Mail::queue('emails.payout_sent', $data, function ($message) use ($user, $data)
        {
            $message->to($user->email, $user->first_name)->subject("Payout of ".html_entity_decode($data['result']['currency']['symbol'], ENT_NOQUOTES, 'UTF-8').$data['payout_amount']." sent");
        });
        return true;
    }

    /**
     * Referral Email Share
     *
     * @param array $emails Friend Emails
     * @return true
     */
    public function referral_email_share($emails)
    {
        $user_id = Auth::user()->user()->id;

        $data['result'] = $user = User::with(['profile_picture'])->whereId($user_id)->value('toArray()');

        $data['travel_credit'] = ReferralSettings::value(4);
        $data['symbol'] = Currency::first()->symbol;

        $data['url'] = url().'/';
        $data['locale'] = App::getLocale();

        $emails = explode(',', $emails);

        foreach($emails as $email) {
            $email = trim($email);
            Mail::queue('emails.referral_email_share', $data, function ($message) use ($user, $data, $email)
            {
                $message->to($email)->subject($user['full_name']." invited you to ".SITE_NAME);
            });
        }
        return true;
    }


    /**
     * Review Remainder
     *
     * @param array  $reservation
     * @param string $type
     *
     * @return true
     */
    public function review_remainder($reservation, $type = 'guest')
    {
        $data['url']    = SiteSettings::where('name', 'site_url')->value('value . '/'');
        $data['locale'] = App::getLocale();

        if ($type == 'guest')
        {
            $email = $reservation->host_users->email;
            $user  = $reservation->users;
        }
        else
        {
            $email = $reservation->users->email;
            $user  = $reservation->host_users;
        }

        $data['users']  = $user;
        $data['result'] = $reservation->toArray();

        $data['profile_picture'] = $user->profile_picture->email_src;
        $data['review_name']     = $user->first_name;

        Mail::queue('emails.review_remainder', $data, function ($message) use ($user, $data, $email)
        {
            $message->to($email)->subject("Write a Review about " . $user->first_name);
        });

        return true;
    }


    /**
     * Review Wrote
     *
     * @param int    $review_id
     * @param string $type
     *
     * @return true
     */
    public function wrote_review($review_id, $type = 'guest')
    {
        $data['url']    = url() . '/';
        $data['locale'] = App::getLocale();

        $reviews = Reviews::find($review_id);

        $email = $reviews->users->email;

        $user = $reviews->users_from;

        $data['users']  = $user;
        $data['result'] = $reviews->toArray();

        $data['review_end_date'] = Reservation::find($reviews->reservation_id)->review_end_date;

        $data['profile_picture'] = $user->profile_picture->src;
        $data['review_name']     = $user->first_name;

        Mail::queue('emails.wrote_review', $data, function ($message) use ($user, $data, $email)
        {
            $message->to($email)->subject($user->first_name . ' wrote you a review');
        });
    }


    /**
     * Review Read
     *
     * @param int    $review_id
     * @param string $type
     *
     * @return true
     */
    public function read_review($review_id, $type = 'guest')
    {
        $data['url']    = url() . '/';
        $data['locale'] = App::getLocale();

        $reviews = Reviews::find($review_id);

        $email = $reviews->users->email;

        $user = $reviews->users_from;

        $data['users']  = $user;
        $data['result'] = $reviews->toArray();

        $data['review_end_date'] = Reservation::find($reviews->reservation_id)->review_end_date;

        $data['profile_picture'] = $user->profile_picture->src;
        $data['review_name']     = $user->first_name;

        Mail::queue('emails.read_review', $data, function ($message) use ($user, $data, $email)
        {
            $message->to($email)->subject('Read ' . $user->first_name . "'s review");
        });
    }


    /**
     * Send accepted Mail to Host
     *
     * @param string $reservation_code Reservation Code
     * @param string $email            Friend Email
     *
     * @return true
     */
    public function accepted($code)
    {
        $data['result']      = Reservation::where('code', $code)->first();
        $user                = $data['result']->host_users;
        $data['hide_header'] = true;

        $data['url']     = url() . '/';
        $data['map_key'] = MAP_KEY;
        $data['locale']  = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $data['result']->id)->with([
            'users'      => function ($query)
            {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            },
            'rooms'      => function ($query)
            {
                $query->with('rooms_address');
            },
            'host_users' => function ($query)
            {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            },
            'currency'
        ]);

        $data['result'] = $data['result']->value('toArray()');
        Mail::queue('emails.accepted', $data, function ($message) use ($user, $data)
        {
            $message->to($data['result']['users']['email'], '')
                ->subject(trans('messages.email.reservation_confirmed') . $data['result']['host_users']['full_name']);
        });

        return true;
    }


    /**
     * Send accepted Mail to Host
     *
     * @param string $reservation_code Reservation Code
     * @param string $email            Friend Email
     *
     * @return true
     */
    public function pre_accepted($code)
    {
        $data['result']      = Reservation::where('id', $code)->first();
        $user                = $data['result']->host_users;
        $data['hide_header'] = true;

        $data['url']     = url() . '/';
        $data['map_key'] = MAP_KEY;
        $data['locale']  = App::getLocale();

        $data['result'] = Reservation::where('reservation.id', $data['result']->id)->with([
            'users'      => function ($query)
            {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            },
            'rooms'      => function ($query)
            {
                $query->with('rooms_address');
            },
            'host_users' => function ($query)
            {
                $query->with('profile_picture')->with('users_verification')->with('reviews');
            },
            'currency'
        ]);

        $data['result'] = $data['result']->value('toArray()');
        // return view('emails.pre_accepted', $data);

        Mail::queue('emails.pre_accepted', $data, function ($message) use ($user, $data)
        {
            $message->to($data['result']['users']['email'], '')
                ->subject(trans('messages.inbox.reservations') . trans('messages.inbox.pre_accepted') . $data['result']['host_users']['full_name']);
        });

        return true;
    }

}
