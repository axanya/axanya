<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\PropertyType;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\User;
use App\Models\Reviews;
use App\Models\RoomsPhotos;
use App\Models\RoomsAddress;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Country;
use App\Models\PayoutPreferences;
use App\Models\Messages;
use App\Models\Reservation;
use App\Models\CouponCode;
use App\Models\RoomsPrice;
use App\Models\Payouts;
use Session;
use DateTime;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use DB;
use Auth;
use Validator;
use JWTAuth;

class PaymentController extends Controller
{

    protected $payment_helper; // Global variable for Helpers instance


    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper         = new Helpers;
    }


    public function pre_payment(Request $request)
    {
        $rules = [
            'room_id'     => 'required|exists:rooms,id',
            'start_date'  => 'required|date_format:Y-m-d|after:today',
            'end_date'    => ' required|date_format:Y-m-d|after:today|after:start_date',
            'total_guest' => 'required'
        ];

        $niceNames = [
            'room_id'     => 'Room Id',
            'start_date'  => 'Start Date',
            'end_date'    => 'End Date',
            'total_guest' => 'Total Guest'
        ];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())

        {
            $error = $validator->messages()->toArray();

            return response()->json([
                    'success_message' => 'Undefiend Request value ',
                    'status_code'     => '0',
                    'error'           => $error
                ]);
        }
        else
        {
            $rooms_total_guest = Rooms::where('id', $request->room_id)->pluck('accommodates');

            if ($request->total_guest < 1 || $request->total_guest > $rooms_total_guest)
            {
                return response()->json([
                    'success_message' => 'Not Valid Total Guest ',
                    'status_code'     => '0'
                ]);
            }
            $user = JWTAuth::parseToken()->authenticate();

            $rooms_info = Rooms::where('id', $request->room_id)->first();

            if ($user->id == $rooms_info->user_id)
            {
                return response()->json([
                    'success_message' => 'You Can Not Book Your Own Listing',
                    'status_code'     => '0'
                ]);
            }
            Session::forget('coupon_code');
            Session::forget('coupon_amount');
            Session::forget('remove_coupon');
            Session::forget('manual_coupon');

            $data = $this->payment_helper->price_calculation($request->room_id, $request->start_date,
                $request->end_date, $request->total_guest, '', $request->change_reservation);

            $data = json_decode($data, true);

            $status_value = @$data['status'];

            if (( ! isset($data['status'])) && ($status_value != 'Not available'))
            {
                $data['rooms'] = Rooms::find($request->room_id)->toArray();

                $result['currency'] = Currency::where('code', $data['currency'])->first();

                $data['host'] = @User::join('profile_picture', function ($join)
                {
                    $join->on('id', '=', 'profile_picture.user_id');
                })
                    ->where('id', $data['rooms']['user_id'])
                    ->where('users.status', 'Active')
                    ->select('id', 'users.first_name', 'users.last_name', 'profile_picture.src')
                    ->get()
                    ->first()
                    ->toArray();

                $data = [
                    'success_message'       => 'Pre Payment Details',
                    'status_code'           => '1',
                    'room_name'             => $data['rooms']['name'],
                    'bedrooms'              => $data['rooms']['bedrooms'],
                    'bathrooms'             => $data['rooms']['bathrooms'],
                    'description'           => $data['rooms']['summary'],
                    'room_type'             => $data['rooms']['room_type_name'],
                    'host_user_name'        => $data['host']['full_name'],
                    'host_user_thumb_image' => $data['host']['src'],
                    'start_date'            => $request->start_date,
                    'end_date'              => $request->end_date,
                    'total_price'           => $data['total'],
                    'currency_code'         => $result['currency']['code'],
                    'currency_symbol'       => $result['currency']['symbol'],
                    'policy_name'           => $data['rooms']['cancel_policy'],
                    // 'policy_details'        =>  '',
                    'per_night_price'       => $data['rooms_price'],
                    'nights_count'          => $data['total_nights'],
                    'service_fee'           => $data['service_fee'],
                ];

                // dd($data);
                return response()->json($data);
            }
            else
            {
                return response()->json([
                        'success_message' => 'Rooms Not available ',
                        'status_code'     => '0'
                    ]);
            }

        }

    }


    public function payment_methods(Request $request)
    {
        $data = [
            'success_message' => 'Payment Methods',
            'status_code'     => '1',
            'payment_method'  => ['PayPal', 'Direct']
        ];

        return response()->json($data);
    }


    public function apply_coupon(Request $request)
    {
        $coupon_details = CouponCode::where('coupon_code', $request->coupon_code)
            ->where('status', 'Active')
            ->get()
            ->first();
        if ( ! empty($coupon_details))
        {
            $currency = Currency::where('code', $coupon_details['currency_code'])->get()->first();

            if ($coupon_details->count())
            {

                $datetime1 = new DateTime();
                $datetime2 = new DateTime($coupon_details->expired_at);

                if ($datetime1 <= $datetime2)
                {
                    $interval_diff = $datetime1->diff($datetime2);
                    $interval      = $interval_diff->days;
                    @$data = [
                        'success_message' => 'Coupon Details',
                        'status_code'     => '1',
                        'coupon_price'    => $coupon_details->amount,
                        'currency_code'   => $coupon_details->currency_code,
                        'currency_symbol' => $currency->symbol
                    ];

                    return response()->json($data);

                }

                else
                {
                    return response()->json([
                            'success_message' => 'Expired_coupon',
                            'status_code'     => '0'
                        ]);
                }

            }


        }
        else
        {
            return response()->json([
                    'success_message' => 'Coupon Code Invalid',
                    'status_code'     => '0'
                ]);
        }

    }


    public function after_payment(Request $request)
    {
        $rules = [
            'room_id'                => 'required|exists:rooms,id',
            'checkin'                => 'required|date_format:Y-m-d|after:today',
            'checkout'               => ' required|date_format:Y-m-d|after:today|after:checkin',
            'paypal_success_message' => 'required',
            'paypal_status_code'     => 'required',
            'paypal_transaction_id'  => 'required',
            'number_of_guests'       => 'required',
            'payment_country_code'   => 'required|exists:country,short_name'

        ];

        $niceNames = [
            'room_id'                => 'Room Id',
            'checkin'                => 'Check In',
            'checkout'               => 'Check Out',
            'paypal_success_message' => 'Paypal Success Message',
            'paypal_status_code'     => 'Paypal Status Code',
            'number_of_guests'       => 'Number Of Guests'
        ];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())

        {
            $error = $validator->messages()->toArray();

            return response()->json([
                    'success_message' => 'Undefiend Request value ',
                    'status_code'     => '0',
                    'error'           => $error
                ]);

        }
        else
        {
            $rooms_total_guest = Rooms::where('id', $request->room_id)->pluck('accommodates');
            if ($request->number_of_guests < 1 || $request->number_of_guests > $rooms_total_guest)
            {
                return response()->json([
                    'success_message' => 'Not Valid Total Guest ',
                    'status_code'     => '0'
                ]);
            }
            $user = JWTAuth::parseToken()->authenticate();

            $rooms_info = Rooms::where('id', $request->room_id)->first();

            if ($user->id == $rooms_info->user_id)
            {
                return response()->json([
                    'success_message' => 'You Can Not Book Your Own Listing',
                    'status_code'     => '0'
                ]);
            }

            $result['ACK']        = $request->paypal_success_message;
            $result['CODE']       = $request->paypal_status_code;
            $room_id              = $request->room_id;
            $checkin              = $request->checkin;
            $checkout             = $request->checkout;
            $transaction_id       = $request->paypal_transaction_id;
            $number_of_guests     = $request->number_of_guests;
            $special_offer_id     = $request->special_offer_id;
            $reservation_id       = $request->reservation_id;
            $message_to_host      = 'success';
            $payment_country_code = strtoupper($request->payment_country_code);

            $data['price_list'] = json_decode($this->payment_helper->price_calculation($room_id, $checkin, $checkout,
                $number_of_guests, $special_offer_id));

            if ( ! isset($data['price_list']->total_nights))
            {

                return response()->json([
                        'success_message' => 'Rooms Date Already Booked',
                        'status_code'     => '0'
                    ]);
            }
            else
            {
                //$user = JWTAuth::parseToken()->authenticate();
                // $user = User::whereId($user->id)->first();

                Session::put('payment_room_id', $room_id);
                Session::put('payment_checkin', date('d-m-Y', strtotime($checkin)));
                Session::put('payment_checkout', date('d-m-Y', strtotime($checkout)));
                Session::put('payment_number_of_guests', $number_of_guests);
                Session::put('payment_booking_type', 'instant_book');
                Session::put('payment_special_offer_id', $special_offer_id);
                Session::put('payment_reservation_id', $reservation_id);
                Session::put('payment_price_list', $data['price_list']);
                Session::put('message_to_host_', $message_to_host);
                Session::put('payment_country', $payment_country_code);
                Session::put('payment_reservation_id', $reservation_id);

                if (@$result['ACK'] == 'payment_success' && @$result['CODE'] == '1')
                {
                    $data = [
                        'room_id'          => Session::get('payment_room_id'),
                        'checkin'          => Session::get('payment_checkin'),
                        'checkout'         => Session::get('payment_checkout'),
                        'number_of_guests' => Session::get('payment_number_of_guests'),
                        'transaction_id'   => $transaction_id,
                        'price_list'       => Session::get('payment_price_list'),
                        'country'          => Session::get('payment_country'),
                        'message_to_host'  => Session::get('message_to_host_'),
                        'paymode'          => 'PayPal'
                    ];

                    $code = $this->store($data);

                    return response()->json([
                            'success_message' => 'Rooms Booked Successfully',
                            'status_code'     => '1'
                        ]);
                }
                else
                {

                    return response()->json([
                            'success_message' => 'Failed',
                            'status_code'     => '0'
                        ]);
                }
            }


        }

    }


    public function store($data)
    {
        if (Session::get('payment_reservation_id'))
        {
            $reservation = Reservation::find(Session::get('payment_reservation_id'));
        }
        else
        {
            $user = JWTAuth::parseToken()->authenticate();
        }

        $user = User::whereId($user->id)->first();
        // echo $user->id; exit;
        $reservation = new Reservation;

        $reservation->room_id          = $data['room_id'];
        $reservation->host_id          = Rooms::find($data['room_id'])->user_id;
        $reservation->user_id          = $user->id;
        $reservation->checkin          = $this->payment_helper->date_convert($data['checkin']);
        $reservation->checkout         = $this->payment_helper->date_convert($data['checkout']);
        $reservation->number_of_guests = $data['number_of_guests'];
        $reservation->nights           = $data['price_list']->total_nights;
        $reservation->per_night        = $data['price_list']->rooms_price;
        $reservation->subtotal         = $data['price_list']->subtotal;
        $reservation->cleaning         = $data['price_list']->cleaning_fee;
        $reservation->additional_guest = $data['price_list']->additional_guest;
        $reservation->security         = $data['price_list']->security_fee;
        $reservation->service          = $data['price_list']->service_fee;
        $reservation->host_fee         = $data['price_list']->host_fee;
        $reservation->total            = $data['price_list']->total;
        $reservation->currency_code    = $data['price_list']->currency;

        if ($data['price_list']->coupon_amount)
        {
            $reservation->coupon_code   = $data['price_list']->coupon_code;
            $reservation->coupon_amount = $coupon_amount = $data['price_list']->coupon_amount;
        }

        $reservation->transaction_id = $data['transaction_id'];
        $reservation->paymode        = $data['paymode'];
        $reservation->cancellation   = Rooms::find($data['room_id'])->cancel_policy;
        $reservation->type           = 'reservation';

        if ($data['paymode'] == 'Credit Card')
        {
            $reservation->first_name  = $data['first_name'];
            $reservation->last_name   = $data['last_name'];
            $reservation->postal_code = $data['postal_code'];
        }

        $reservation->country = $data['country'];
        $reservation->status  = (Session::get('payment_booking_type') == 'instant_book') ? 'Accepted' : 'Pending';

        $reservation->save();

        if (@$data['price_list']->coupon_code == 'Travel_Credit')
        {
            $referral_friend = Referrals::whereFriendId($user->id)->get();
            foreach ($referral_friend as $row)
            {
                $friend_credit = $row->friend_credited_amount;
                if ($coupon_amount != 0)
                {
                    if ($friend_credit <= $coupon_amount)
                    {
                        $referral                         = Referrals::find($row->id);
                        $referral->friend_credited_amount = 0;
                        $referral->save();
                        $coupon_amount = $coupon_amount - $friend_credit;

                        $applied_referral                 = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id    = $row->id;
                        $applied_referral->amount         = $friend_credit;
                        $applied_referral->type           = 'friend';
                        $applied_referral->currency_code  = $data['price_list']->currency;
                        $applied_referral->save();
                    }
                    else
                    {
                        $referral                         = Referrals::find($row->id);
                        $referral->friend_credited_amount = $friend_credit - $coupon_amount;
                        $referral->save();

                        $applied_referral                 = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id    = $row->id;
                        $applied_referral->amount         = $coupon_amount;
                        $applied_referral->type           = 'friend';
                        $applied_referral->currency_code  = $data['price_list']->currency;
                        $applied_referral->save();
                        $coupon_amount = 0;
                    }
                }
            }
            $referral_user = Referrals::whereUserId($user->id)->get();
            foreach ($referral_user as $row)
            {
                $user_credit = $row->credited_amount;
                if ($coupon_amount != 0)
                {
                    if ($user_credit <= $coupon_amount)
                    {
                        $referral                  = Referrals::find($row->id);
                        $referral->credited_amount = 0;
                        $referral->save();
                        $coupon_amount = $coupon_amount - $user_credit;

                        $applied_referral                 = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id    = $row->id;
                        $applied_referral->amount         = $user_credit;
                        $applied_referral->type           = 'main';
                        $applied_referral->currency_code  = $data['price_list']->currency;
                        $applied_referral->save();
                    }
                    else
                    {
                        $referral                  = Referrals::find($row->id);
                        $referral->credited_amount = $user_credit - $coupon_amount;
                        $referral->save();

                        $applied_referral                 = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id    = $row->id;
                        $applied_referral->amount         = $coupon_amount;
                        $applied_referral->type           = 'main';
                        $applied_referral->currency_code  = $data['price_list']->currency;
                        $applied_referral->save();
                        $coupon_amount = 0;
                    }
                }
            }
        }

        do
        {
            $code       = $this->getCode(6, $reservation->id);
            $check_code = Reservation::where('code', $code)->get();
        }
        while (empty($check_code));

        $reservation_code = Reservation::find($reservation->id);

        $reservation_code->code = $code;

        $reservation_code->save();

        $days = $this->get_days($data['checkin'], $data['checkout']);

        // Update Calendar
        for ($j = 0; $j < count($days) - 1; $j++)
        {
            $calendar_data = [
                'room_id' => $data['room_id'],
                'date'    => $days[$j],
                'status'  => 'Not available'
            ];

            Calendar::updateOrCreate(['room_id' => $data['room_id'], 'date' => $days[$j]], $calendar_data);
        }

        if ($reservation_code->status == 'Accepted')
        {
            $payouts = new Payouts;

            $payouts->reservation_id = $reservation_code->id;
            $payouts->room_id        = $reservation_code->room_id;
            $payouts->user_id        = $reservation_code->host_id;
            $payouts->user_type      = 'host';
            $payouts->amount         = $reservation_code->host_payout;
            $payouts->currency_code  = $reservation_code->currency_code;
            $payouts->status         = 'Future';

            $payouts->save();
        }

        $message  = new Messages;
        $messages = '';
        if (@$data['message_to_host'])
        {
            $messages = $this->helper->phone_email_remove($data['message_to_host']);
        }

        $message->room_id        = $data['room_id'];
        $message->reservation_id = $reservation->id;
        $message->user_to        = $reservation->host_id;
        $message->user_from      = $reservation->user_id;
        $message->message        = $messages;
        $message->message_type   = 2;
        $message->read           = 0;

        $message->save();

        //$email_controller = new EmailController;
        //$email_controller->booking($reservation->id);

        Session::forget('payment_room_id');
        Session::forget('payment_checkin');
        Session::forget('payment_checkout');
        Session::forget('payment_number_of_guests');
        Session::forget('payment_booking_type');
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');

        return $code;
    }


    /**
     * Generate Reservation Code
     *
     * @param date $length Code Length
     * @param date $seed   Reservation Id
     *
     * @return string Reservation Code
     */
    public function getCode($length, $seed)
    {
        $code         = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "0123456789";

        mt_srand($seed);

        for ($i = 0; $i < $length; $i++)
        {
            $code .= $codeAlphabet[mt_rand(0, strlen($codeAlphabet) - 1)];
        }

        return $code;
    }


    /**
     * Get days between two dates
     *
     * @param date $sStartDate Start Date
     * @param date $sEndDate   End Date
     *
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {
        $sStartDate   = $this->payment_helper->date_convert($sStartDate);
        $sEndDate     = $this->payment_helper->date_convert($sEndDate);
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;
        }

        return $aDays;
    }


    public function currency_change(Request $request)
    {
        $rules = ['currency_code' => 'required|exists:currency,code'];

        $niceNames = ['currency_code' => 'Currency Code'];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->setAttributeNames($niceNames);

        if ($validator->fails())

        {
            $error = $validator->messages()->toArray();

            return response()->json([
                'success_message' => 'Invalid Currency Code',
                'status_code'     => '0'
            ]);
        }
        else
        {
            $currency_code_request  = $request->currency_code;
            $currency_code_original = strtoupper($request->currency_code);
            if ($currency_code_request == $currency_code_original)
            {
                $user = JWTAuth::parseToken()->authenticate();

                $user = User::whereId($user->id)->first();

                DB::table('users')->where('id', $user->id)->update(['currency_code' => $request->currency_code]);
                Session::put('currency', $request->currency_code);

                return response()->json([
                    'success_message' => 'Currency Code Changed...',
                    'status_code'     => '1'
                ]);


            }
            else
            {
                return response()->json([
                    'success_message' => 'Invalid Currency Code',
                    'status_code'     => '0'
                ]);
            }


        }

    }

}
    
