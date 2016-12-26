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
use App\Models\RoomsPrice;
use App\Models\RoomsDescription;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Validator;
use DB;
use Auth;

class RoomsController extends Controller
{

    protected $payment_helper; // Global variable for Helpers instance


    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper         = new Helpers;
    }


    public function rooms_detail(Request $request)
    {
        $rules = ['room_id' => 'required|exists:rooms,id'];

        $niceNames = ['room_id' => 'Room Id'];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())

        {
            return response()->json([
                    'success_message' => 'Invalid Room Id',
                    'status_code'     => '0'
                ]);
            exit;
        }
        else
        {

            $data['room_id'] = $request->room_id;

            $data['url'] = url() . '/rooms/' . $data['room_id'];

            $data['result'] = Rooms::find($request->room_id);
            $host_name      = User::join('profile_picture', function ($join)
            {
                $join->on('users.id', '=', 'profile_picture.user_id');
            })->where('id', $data['result']->user_id)->select('first_name', 'last_name', 'src as user_image')->first();

            if (count($data['result']) == 0)
                // abort('404');

            {
                $data['amenities'] = Amenities::selected($request->room_id);
            }

            $data['safety_amenities'] = Amenities::selected_security($request->room_id);

            $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->room_id)->get();

            foreach ($data['rooms_photos'] as $images)
            {

                if ($images['featured'] == 'Yes')
                {
                    $image_default[] = url() . '/images/rooms/' . $request->room_id . '/' . $images['name'];
                }
                else
                {
                    $image_undefault[] = url() . '/images/rooms/' . $request->room_id . '/' . $images['name'];
                }

            }
            $image_collection = array_merge($image_default, $image_undefault);

            $data['reviews_details'] = @Reviews::where('room_id', $request->room_id)
                ->where('review_by', 'guest')
                ->get()
                ->first();

            $data['reviews_details_user'] = @User::join('profile_picture', function ($join)
            {
                $join->on('id', '=', 'profile_picture.user_id');
            })->where('id', $data['reviews_details']->user_from)->where('users.status', 'Active')->get()->first();

            $data['reviews_details_host'] = @User::join('profile_picture', function ($join)
            {
                $join->on('id', '=', 'profile_picture.user_id');
            })->where('id', $data['reviews_details']->user_to)->where('users.status', 'Active')->get()->first();

            $whishlist_count = @DB::table('saved_wishlists')
                ->where('user_id', $data['reviews_details_user']['id'])
                ->where('room_id', $data['room_id'])
                ->count();
            if ($whishlist_count > '0')
            {

                $whishlist['is_whishlist'] = 'Yes';

            }
            else
            {
                $whishlist['is_whishlist'] = 'No';

            }
            //return response()->json( $data); exit;

            $rooms_address = $data['result']->rooms_address;

            $latitude = $rooms_address->latitude;

            $longitude = $rooms_address->longitude;

            if ($request->checkin != '' && $request->checkout != '')
            {
                $data['checkin']  = date('m/d/Y', strtotime($request->checkin));
                $data['checkout'] = date('m/d/Y', strtotime($request->checkout));
                $data['guests']   = $request->guests;
            }
            else
            {
                $data['checkin']  = '';
                $data['checkout'] = '';
                $data['guests']   = '';
            }

            $data['similar'] = Rooms::join('rooms_address', function ($join)
            {
                $join->on('rooms.id', '=', 'rooms_address.room_id');
            })
                ->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
                ->having('distance', '<=', 30)
                ->where('rooms.id', '!=', $request->room_id)
                ->where('rooms.status', 'Listed')
                ->whereHas('users', function ($query)
                {
                    $query->where('users.status', 'Active');
                })
                ->get();

            //similar query data split
            foreach ($data['similar'] as $similar)
            {

                $whishlist_count = @DB::table('saved_wishlists')
                    ->where('user_id', $similar->user_id)
                    ->where('room_id', $similar->id)
                    ->count();

                if ($whishlist_count > '0')
                {

                    $whishlist['is_whishlist'] = 'Yes';

                }
                else
                {
                    $whishlist['is_whishlist'] = 'No';

                }

                $result['price'] = RoomsPrice::where('room_id', $similar->id)->get()->lists('night');

                $data['rating_value'] = Reviews::where('room_id', $similar->id)
                    ->where('review_by', 'guest')
                    ->pluck('rating');

                @$similar_list[] = [
                    'room_id'          => $similar->id,
                    'user_id'          => $similar->user_id,
                    'room_price'       => $result['price']['0'],
                    'room_name'        => $similar->name,
                    'room_thumb_image' => $similar->photo_name,
                    'rating_value'     => $data['rating_value'],
                    'reviews_value'    => $similar->reviews_count,
                    'is_whishlist'     => $whishlist['is_whishlist']
                ];

            }

            // end similar data split
            $data['title'] = $data['result']->name . ' in ' . $data['result']->rooms_address->city;

            $id = $request->room_id;

            $result['not_avilable'] = Calendar::where('room_id', $id)
                ->where('status', 'Not available')
                ->get()
                ->lists('date');

            $result['price'] = RoomsPrice::where('room_id', $id)->get()->lists('night');

            $rooms_details = [

                'success_message'      => 'Room Detail Listed Successfully',
                'status_code'          => '1',
                'room_id'              => intval($data['room_id']),
                'room_price'           => $result['price']['0'],
                'room_name'            => $data['result']['name'],
                'room_images'          => $image_collection,
                'room_share_url'       => $data['url'],
                'is_whishlist'         => $whishlist['is_whishlist'],
                'rating_value'         => $data['reviews_details']['rating'] != null ? $data['reviews_details']['rating'] : 0,
                'host_user_name'       => $host_name['first_name'] . $host_name['last_name'],
                'room_type'            => $data['result']['room_type'],
                'host_user_image'      => url() . '/images/' . $host_name['user_image'],
                'no_of_guest'          => $data['result']['accommodates'],
                'no_of_beds'           => $data['result']['beds'],
                'no_of_bedrooms'       => $data['result']['bedrooms'],
                'no_of_bathrooms'      => $data['result']['bathrooms'],
                'amenities_values'     => explode(",", $data['result']['amenities']),
                'locaiton_name'        => $data['result']['rooms_address']['address_line_1'] . ',' . $data['result']['rooms_address']['address_line_2'] . ',' . $data['result']['rooms_address']['city'] . ',' . $data['result']['rooms_address']['state'] . ',' . $data['result']['rooms_address']['country'] . ',' . $data['result']['rooms_address']['postal_code'],
                'loc_latidude'         => $data['result']['rooms_address']['latitude'],
                'loc_longidude'        => $data['result']['rooms_address']['longitude'],
                'review_user_name'     => $data['reviews_details_user']['first_name'] . $data['reviews_details_user']['last_name'],
                'review_user_image'    => $data['reviews_details_user']['src'],
                'review_date'          => $data['reviews_details_user']['created_at'],
                'review_message'       => $data['reviews_details_user']['comments'],
                'review_value'         => $data['result']['reviews_count'],
                'room_detail'          => $data['result']['summary'],
                'check_in_time'        => 'Flexible',
                'check_out_time'       => 'Flexible',
                'similar_list_details' => @$similar_list
            ];

            return response()->json($rooms_details);
            // dd($data['similar']); //*/
        }
    }


    public function review_detail(Request $request)
    {
        $rules = ['room_id' => 'required|exists:rooms,id'];

        $niceNames = ['room_id' => 'Room Id'];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())
        {
            $error = $validator->messages()->toArray();

            return response()->json([
                    'success_message' => 'Invalid Room Id',
                    'status_code'     => '0',
                    'error'           => $error
                ]);
        }
        else
        {
            $data = @User::join('reviews', function ($join)
            {
                $join->on('users.id', '=', 'reviews.user_from');
            })
                ->join('profile_picture', function ($join)
                {
                    $join->on('users.id', '=', 'profile_picture.user_id');
                })
                ->where('reviews.room_id', $request->room_id)
                ->where('review_by', 'guest')
                ->where('users.status', 'Active')
                ->get()
                ->toArray();

            if ( ! empty($data))
            {

                $success_message = 'Review Detail Listed Successfully';
                $status_code     = '1';

            }
            else
            {

                $success_message = 'No Review Detail';
                $status_code     = '0';

            }
            $review_details[] = [
                'success_message' => $success_message,
                'status_code'     => $status_code
            ];

            foreach ($data as $review)
            {
                $reviews_count = Reviews::where('room_id', $request->room_id)->where('review_by', 'guest')->count();

                $review_details[] = [
                    'total_review'      => $reviews_count,
                    'reivew_value'      => $review['rating'],
                    'review_user_name'  => $review['first_name'] . $review['last_name'],
                    'review_user_image' => $review['src'],
                    'review_date'       => $review['created_at'],
                    'review_message'    => $review['comments']
                ];
            }

            //dd($data); exit;
            return response()->json($review_details);

        }
    }


    public function calendar_availability(Request $request)
    {
        $rules = ['room_id' => 'required|exists:rooms,id'];

        $niceNames = ['room_id' => 'Room Id'];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())
        {
            $error = $validator->messages()->toArray();

            return response()->json([
                    'success_message' => 'Failed',
                    'status_code'     => '0',
                    'error'           => $error
                ]);

        }
        else
        {

            //$date = date('Y-m-d');
            $date_check = date('Y-m-d');

            $rooms_count = Rooms::where('id', $request->room_id)->where('status', 'Listed')->get()->toArray();

            if ($request->room_id != '' && ! empty($rooms_count))

            {

                $data = Calendar::where('room_id', $request->room_id)
                    ->where('status', 'Not available')
                    ->where('date', '>=', $date_check)
                    ->get()
                    ->lists('date')
                    ->toArray();

                if ( ! empty($data))
                {
                    $data = [
                        'success_message' => 'Calendar Blocked Dates Listed Successfully',
                        'status_code'     => '1',
                        'blocked_dates'   => $data
                    ];

                    return response()->json($data);
                }
                else
                {

                    $data = [
                        'success_message' => 'Not Available',
                        'status_code'     => '0',
                    ];

                }

                return response()->json($data);
            }
            elseif ($request->room_id == '')
            {
                return response()->json([
                        'success_message' => 'Undefind Room Id',
                        'status_code'     => '0'
                    ]);
            }
            elseif (empty($rooms_count))
            {
                return response()->json([
                        'success_message' => 'Invalid Room Id',
                        'status_code'     => '0'
                    ]);
            }

        }

    }


    public function country_list(Request $request)
    {
        $data[] = [
            'success_message' => 'Country Listed Successfully',
            'status_code'     => '1'
        ];
        $data[] = Country::select('id as country_id', 'long_name as country_name', 'short_name as country_code')->get();

        return response()->json($data);

    }


    public function calendar_availability_status(Request $request)
    {
        $rules = [
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date_format:Y-m-d|after:today',
            'end_date'   => ' required|date_format:Y-m-d|after:today|after:start_date'
        ];

        $niceNames = [
            'room_id'    => 'Room Id',
            'start_date' => 'Start Date',
            'end_date'   => 'End Date',

        ];

        $messages  = ['required' => ':attribute is required.'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())
        {
            $error = $validator->messages()->toArray();

            return response()->json([
                    'success_message' => 'failed',
                    'status_code'     => '0',
                    'error'           => $error
                ]);
        }
        else
        {
            $rooms_count = Rooms::where('id', $request->room_id)->where('status', 'Listed')->get()->toArray();
            if ( ! empty($rooms_count))
            {
                //return $this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->guest_count ,'',$request->change_reservation);
                $data = $this->payment_helper->price_calculation($request->room_id, $request->start_date,
                    $request->end_date, '', '', '');

                $data = json_decode($data, true);

                $result = @$data['status'];

                if ((isset($data['status'])) && ($result == 'Not available'))
                {

                    return response()->json([
                        'success_message' => 'Not Available',
                        'status_code'     => '0'
                    ]);
                }
                else
                {
                    return response()->json([
                        'success_message'  => 'Available',
                        'status_code'      => '1',
                        'pernight_price'   => $data['rooms_price'],
                        'availability_msg' => 'Rooms Available'
                    ]);

                }

            }
            else
            {
                return response()->json([
                    'success_message' => 'Invalid Room Id',
                    'status_code'     => '0'
                ]);

            }
        }
    }


    public function house_rules(Request $request)
    {
        $rooms_count = Rooms::where('id', $request->room_id)->where('status', 'Listed')->get()->toArray();

        if ($request->room_id != '' && ! empty($rooms_count))
        {
            $data = RoomsDescription::where('room_id', $request->room_id)->pluck('house_rules');
            if ( ! empty($data))
            {
                return response()->json([
                    'success_message' => 'House Rules Details',
                    'status_code'     => '1',
                    'house_rules'     => $data,
                ]);
            }
            else
            {
                return response()->json([
                        'success_message' => 'Invalid Room Id',
                        'status_code'     => '0'
                    ]);
            }
        }
        elseif ($request->room_id == '')
        {
            return response()->json([
                    'success_message' => 'Undefind Room Id',
                    'status_code'     => '0'
                ]);
        }
        elseif (empty($rooms_count))
        {
            return response()->json([
                    'success_message' => 'Invalid Room Id',
                    'status_code'     => '0'
                ]);
        }

    }


    public function maps(Request $request)
    {

        $data = Rooms::join('rooms_price', function ($join)
        {
            $join->on('rooms.id', '=', 'rooms_price.room_id');
        })->join('rooms_address', function ($join)
            {
                $join->on('rooms.id', '=', 'rooms_address.room_id');
            })->where('rooms.status', 'Listed')->get()->toArray();

        $result[] = [
            'success_message' => 'Maps Details Listed Successfully',
            'status_code'     => '1',
        ];
        foreach ($data as $value)
        {
            $result[] = [
                'room_price'       => $value['night'],
                'room_name'        => $value['name'],
                'room_thumb_image' => $value['photo_name'],
                'rating_value'     => $value['overall_star_rating']['rating_value'],
                'reviews_count'    => $value['reviews_count'],
                'is_whishlist'     => $value['overall_star_rating']['is_wishlist'],
                'loc_latidude'     => $value['latitude'],
                'loc_longidude'    => $value['longitude']
            ];
        }
        if ( ! empty($data))
        {
            return response()->json($result);
        }
        else
        {
            return response()->json([
                'success_message' => 'No Rooms Available',
                'status_code'     => '0'
            ]);
        }
    }
}
    
