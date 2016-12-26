<?php

/**
 * Referrals Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Referrals
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ReferralSettings;
use App\Models\Referrals;
use App\Models\User;
use App\Models\Currency;
use Auth;
use App\Http\Controllers\EmailController;
use Validator;

class ReferralsController extends Controller
{

    public function __construct()
    {
        Validator::extend("emails", function ($attribute, $value, $parameters)
        {
            $rules  = [
                'email' => 'required|email',
            ];
            $emails = explode(',', $value);
            foreach ($emails as $email)
            {
                $data      = [
                    'email' => $email
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails())
                {
                    return false;
                }
            }

            return true;
        });
    }

    public function invite()
    {
    	$data['result'] = ReferralSettings::first();

    	if(Auth::user()->check()) {
    		$data['username'] = Auth::user()->user()->id;
    		$data['referrals'] = Referrals::with(['users', 'friend_users' => function($query){
    			$query->with('profile_picture');
    		}])->whereUserId(Auth::user()->user()->id)->orderBy('referrals.id','desc')->get();
            $data['credited_amount'] = Referrals::whereUserId(Auth::user()->user()->id)
                    ->get()
                    ->sum('credited_amount') + Referrals::whereFriendId(Auth::user()->user()->id)
                    ->get()
                    ->sum('friend_credited_amount');
    		return view('referrals.invite_user', $data);
    	}
    	else
    		return view('referrals.invite', $data);
    }

    public function invites()
    {
        $data['result'] = ReferralSettings::first();

        if(Auth::user()->check()) {
            $data['username'] = Auth::user()->user()->id;
            $data['referrals'] = Referrals::with(['users', 'friend_users' => function($query){
                $query->with('profile_picture');
            }])->whereUserId(Auth::user()->user()->id)->orderBy('referrals.id','desc')->get();
            $data['credited_amount'] = Referrals::whereUserId(Auth::user()->user()->id)->sum('credited_amount') + Referrals::whereFriendId(Auth::user()->user()->id)->sum('friend_credited_amount');
            return view('referrals.invites_user', $data);
        }
        else
            return view('referrals.invites', $data);
    }

    public function invite_referral(Request $request)
    {
    	$data['referral'] = ReferralSettings::first();
    	$data['result'] = User::find($request->username);

    	return view('referrals.invite_referral_user', $data);
    }

    public function share_email(Request $request, EmailController $email_controller)
    {
        $validator = Validator::make($request->all(), [
            'emails' => 'required|emails'
        ]);

        if ($validator->fails())
        {
            return 'false';
        }
        else
        {
            $email_controller->referral_email_share($request->emails);

            return 'true';
        }
    }
}
