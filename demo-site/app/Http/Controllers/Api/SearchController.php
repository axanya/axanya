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
use App\Models\RoomsPrice;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use DB;
use Auth;
use Session;

class SearchController extends Controller
{

    protected $payment_helper; // Global variable for Helpers instance


    /**
     * Constructor to Set PaymentHelper instance in Global variable
     *
     * @param array $payment Instance of PaymentHelper
     */
    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        DB::enableQueryLog();
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    function explore_details(Request $request)
    {
        if ($request->page != '' && $request->page != '0')
        {
            $full_address = $request->input('location');

            $checkin       = $request->input('checkin');
            $checkout      = $request->input('checkout');
            $guest         = $request->input('guests');
            $bathrooms     = $request->input('bathrooms');
            $bedrooms      = $request->input('bedrooms');
            $beds          = $request->input('beds');
            $property_type = $request->input('property_type');
            $room_type     = $request->input('room_type');
            $amenities     = $request->input('amenities');
            $min_price     = $request->input('min_price');
            $max_price     = $request->input('max_price');
            $map_details   = $request->input('map_details');
            $instant_book  = $request->input('instant_book');

            $data['viewport'] = '';

            if ( ! $min_price)
            {
                $min_price = $this->payment_helper->currency_convert('USD', '', 0);
                $max_price = $this->payment_helper->currency_convert('USD', '', 750);
            }

            if ( ! is_array($room_type))
            {
                if ($room_type != '')
                {
                    $room_type = explode(',', $room_type);
                }
                else
                {
                    $room_type = [];
                }
            }

            if ( ! is_array($property_type))
            {
                if ($property_type != '')
                {
                    $property_type = explode(',', $property_type);
                }
                else
                {
                    $property_type = [];
                }
            }

            if ( ! is_array($amenities))
            {
                if ($amenities != '')
                {
                    $amenities = explode(',', $amenities);
                }
                else
                {
                    $amenities = [];
                }
            }

            $property_type_val   = [];
            $rooms_whereIn       = [];
            $room_type_val       = [];
            $rooms_address_where = [];

            $address = str_replace([" ", "%2C"], ["+", ","], "$full_address");
            $geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . MAP_SERVER_KEY . '&address=' . $address . '&sensor=false&libraries=places');
            $json    = json_decode($geocode);

            if (@$json->results)
            {
                foreach ($json->results as $result)
                {
                    foreach ($result->address_components as $addressPart)
                    {
                        if ((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
                        {
                            $city1                                     = $addressPart->long_name;
                            $rooms_address_where['rooms_address.city'] = $city1;
                        }
                        if ((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political',
                                $addressPart->types))
                        )
                        {
                            $state                                      = $addressPart->long_name;
                            $rooms_address_where['rooms_address.state'] = $state;
                        }
                        if ((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))
                        {
                            $country                                      = $addressPart->short_name;
                            $rooms_address_where['rooms_address.country'] = $country;
                        }
                    }
                }
            }

            if ($map_details != '')
            {
                $map_detail = explode('~', $map_details);
                $zoom       = $map_detail[0];
                $bounds     = $map_detail[1];
                $minLat     = $map_detail[2];
                $minLong    = $map_detail[3];
                $maxLat     = $map_detail[4];
                $maxLong    = $map_detail[5];
                $cLat       = $map_detail[6];
                $cLong      = $map_detail[7];
            }
            else
            {
                if (@$json->{'results'})
                {
                    // $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                    // $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                    $data['viewport'] = $json->{'results'}[0]->{'geometry'}->{'viewport'};

                    $minLat  = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lat'};
                    $maxLat  = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lat'};
                    $minLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lng'};
                    $maxLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lng'};
                }
                else
                {
                    $data['lat']  = 0;
                    $data['long'] = 0;

                    $minLat  = -1000;
                    $maxLat  = 1000;
                    $minLong = -1000;
                    $maxLong = 1000;
                }
            }
            $users_where['users.status'] = 'Active';

            $checkin  = date('Y-m-d', strtotime($checkin));
            $checkout = date('Y-m-d', strtotime($checkout));

            $days = $this->get_days($checkin, $checkout);

            unset($days[count($days) - 1]);

            $calendar_where['date'] = $days;

            $not_available_room_ids = Calendar::whereIn('date', $days)
                ->whereStatus('Not available')
                ->distinct()
                ->lists('room_id');

            $rooms_where['rooms.accommodates'] = $guest;

            $rooms_where['rooms.status'] = 'Listed';

            if ($bathrooms)
            {
                $rooms_where['rooms.bathrooms'] = $bathrooms;
            }

            if ($bedrooms)
            {
                $rooms_where['rooms.bedrooms'] = $bedrooms;
            }

            if ($beds)
            {
                $rooms_where['rooms.beds'] = $beds;
            }

            if (count($property_type))
            {
                foreach ($property_type as $property_value)
                {
                    array_push($property_type_val, $property_value);
                }

                $rooms_whereIn['rooms.property_type'] = $property_type_val;
            }

            if (count($room_type))
            {
                foreach ($room_type as $room_value)
                {
                    array_push($room_type_val, $room_value);
                }

                $rooms_whereIn['rooms.room_type'] = $room_type_val;
            }

            if ($instant_book == 1)
            {
                $rooms_where['rooms.booking_type'] = 'instant_book';
            }

            $currency_rate = Currency::where('code', Currency::find(1)->session_code)->value('rate');

            $max_price_check = $this->payment_helper->currency_convert('', 'USD', $max_price);

            $rooms = Rooms::with([
                'rooms_address'   => function ($query) use ($minLat, $maxLat, $minLong, $maxLong)
                {
                },
                'rooms_price'     => function ($query) use ($min_price, $max_price)
                {
                    $query->with('currency');
                },
                'users'           => function ($query) use ($users_where)
                {
                    $query->with('profile_picture')->where($users_where);
                },
                'saved_wishlists' => function ($query)
                {
                    $query->where('user_id', @Auth::user()->user()->id);
                }
            ])
                ->whereHas('rooms_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong)
                {
                    $query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
                })
                ->whereHas('rooms_price',
                    function ($query) use ($min_price, $max_price, $currency_rate, $max_price_check)
                    {
                        $query->join('currency', 'currency.code', '=', 'rooms_price.currency_code');
                        if ($max_price_check >= 750)
                        {
                            $query->whereRaw('((night / currency.rate) * ' . $currency_rate . ') >= ' . $min_price);
                        }
                        else
                        {
                            $query->whereRaw('((night / currency.rate) * ' . $currency_rate . ') >= ' . $min_price . ' and ((night / currency.rate) * ' . $currency_rate . ') <= ' . $max_price);
                        }
                    })
                ->whereHas('users', function ($query) use ($users_where)
                {
                    $query->where($users_where);
                })
                ->whereNotIn('id', $not_available_room_ids);

            if ($rooms_where)
            {
                foreach ($rooms_where as $row => $value)
                {
                    if ($row == 'rooms.accommodates' || $row == 'rooms.bathrooms' || $row == 'rooms.bedrooms' || $row == 'rooms.beds')
                    {
                        $operator = '>=';
                    }
                    else
                    {
                        $operator = '=';
                    }

                    if ($value == '')
                    {
                        $value = 0;
                    }

                    $rooms = $rooms->where($row, $operator, $value);
                }
            }

            if ($rooms_whereIn)
            {
                foreach ($rooms_whereIn as $row_rooms_whereIn => $value_rooms_whereIn)
                {
                    $rooms = $rooms->whereIn($row_rooms_whereIn, array_values($value_rooms_whereIn));
                }
            }

            if (count($amenities))
            {
                foreach ($amenities as $amenities_value)
                {
                    $rooms = $rooms->whereRaw('find_in_set(' . $amenities_value . ', amenities)');
                }
            }

            $rooms        = $rooms->orderByRaw('RAND(1234)')->paginate(10)->toJson();
            $data         = [
                'success_message' => 'Rooms Details Successfully',
                'status_code'     => '1'
            ];
            $data_success = json_encode($data);

            $totalcount = json_decode($rooms);
            //dd($totalcount);exit;
            if ($totalcount->total == 0 || empty($totalcount->data))
            {
                return response()->json([
                    'success_message' => 'No Data Found',
                    'status_code'     => '0'
                ]);
            }
            else
            {
                // return json_encode(array_merge(json_decode($data, true),json_decode($rooms, true)));
                $data_result = json_decode($rooms, true);
                $count       = count($data_result['data']);

                for ($i = 0; $i < $count; $i++)
                {
                    @$result[] = [
                        'room_id'          => $data_result['data'][$i]['id'],
                        'room_price'       => $data_result['data'][$i]['rooms_price']['night'],
                        'room_name'        => $data_result['data'][$i]['name'],
                        'room_thumb_image' => url() . '/images/' . $data_result['data'][$i]['photo_name'],
                        'rating_value'     => $data_result['data'][$i]
                        ['overall_star_rating']['rating_value'] != null ? $data_result['data'][$i]
                        ['overall_star_rating']['rating_value'] : 0,
                        'reviews_count'    => $data_result['data'][$i]
                        ['reviews_count'] != null ? $data_result['data'][$i]['reviews_count'] : 0,

                        'is_whishlist' => $data_result['data'][$i]['overall_star_rating']['is_wishlist']
                    ];
                }

//$this->currency_convert($pen->currency_code,$reservation_currency_code,$host_amount);

                $result = ['total_page' => $data_result['total'], 'data' => $result];
                $data   = json_encode($result);

                return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)));

            }
            // echo $result;
            /* $rooms = $rooms->get();
            $queries = DB::getQueryLog();
            // $last_query = end($queries);
            print_r($queries);exit;*/
        }
        /*$ip ='72.229.28.185';
         $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
        dd($result['geoplugin_currencyCode']); exit;
        */

        else
        {
            return response()->json([
                'success_message' => 'Undefind Page No',
                'status_code'     => '0'
            ]);

        }
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
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;
        }

        return $aDays;
    }

}
