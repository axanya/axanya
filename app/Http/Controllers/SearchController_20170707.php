<?php

/**
 * Search Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Search
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\PropertyType;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\RoomsPhotos;
use App\Models\RoomsAddress;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Places;
use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use DB;
use Auth;

class SearchController extends Controller
{
    protected $payment_helper; // Global variable for Helpers instance

    /**
     * Constructor to Set PaymentHelper instance in Global variable
     *
     * @param array $payment   Instance of PaymentHelper
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
    public function index(Request $request)
    {
        $full_address = $request->input('location');
        $address      = str_replace(" ", "+", "$full_address");
        $geocode      = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.SERVER_MAP_KEY.'&address='.$address.'&sensor=false');
        $json         = json_decode($geocode);
        
        if(@$json->{'results'})
        {
            $data['lat']  = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        }
        else
        {
            $data['lat']  = 0;
            $data['long'] = 0;
        }

        $rooms = Rooms::join(
                    'rooms_price',function($join){
                        $join->on('rooms.id','=','rooms_price.room_id');
                    })->
                    join('rooms_address', function($join) {
                        $join->on('rooms.id', '=', 'rooms_address.room_id');
                    })->
                    join('users',function($join){
                        $join->on('rooms.user_id','=',"users.id");
                    })->where('rooms_address.latitude')->where('rooms.status','Listed')->get();

        $data['location']           = $request->input('location');
        $data['checkin']            = $request->input('checkin');
        $data['checkout']           = $request->input('checkout');
        $data['guest']              = $request->input('guest');
        $data['bedrooms']           = $request->input('bedrooms');
        $data['bathrooms']          = $request->input('bathrooms');
        $data['beds']               = $request->input('beds');
        $data['property_type']      = $request->input('property_type');
        $data['room_type']          = $request->input('room_type');
        $data['amenities']          = $request->input('amenities');
        $data['min_price']          = $request->input('min_price');
        $data['max_price']          = $request->input('max_price');

        $data['room_type']          = RoomType::dropdown();
        $data['property_type']      = PropertyType::dropdown();
        $data['amenities']          = Amenities::active_all();
        $data['amenities_type']     = AmenitiesType::active_all();

        $data['property_type_selected'] = explode(',', $request->input('property_type'));
        $data['room_type_selected'] = explode(',', $request->input('room_type'));
        $data['amenities_selected'] = explode(',', $request->input('amenities'));
        $data['currency_symbol']    = Currency::find(1)->symbol;

        $data['default_min_price'] = $this->payment_helper->currency_convert('USD', '', 0);
        $data['default_max_price'] = $this->payment_helper->currency_convert('USD', '', 750);

        if(!$data['min_price'])
        {
            $data['min_price'] = $data['default_min_price'];
            $data['max_price'] = $data['default_max_price'];
        }

        $data['max_price_check'] = $this->payment_helper->currency_convert('', 'USD', $data['max_price']);

        return view('search.search', $data);
    }

    /**
     * Ajax Search Result
     *
     * @param array $request Input values
     * @return json Search results
     */
    function searchResult(Request $request)
    {
        $full_address  = $request->input('location');

        $checkin       = $request->input('checkin');
        $checkout      = $request->input('checkout');
        $guest         = $request->input('guest');
        $bathrooms     = $request->input('bathrooms');
        $bedrooms      = $request->input('bedrooms');
        $beds          = $request->input('beds');
        $property_type = $request->input('property_type');
        $room_type     = $request->input('room_type');
        $amenities     = $request->input('amenities');
        $min_price     = $request->input('min_price');
        $max_price     = $request->input('max_price');
        $map_details   = $request->input('map_details');

        if(!$min_price)
        {
            $min_price = $this->payment_helper->currency_convert('USD', '', 0);
            $max_price = $this->payment_helper->currency_convert('USD', '', 750);
        }

        if(!is_array($room_type))
        {
            if($room_type != '')
             $room_type = explode(',', $room_type);
            else
             $room_type = [];
        }

        if(!is_array($property_type))
        {
            if($property_type != '')
             $property_type = explode(',', $property_type);
            else
             $property_type = [];
        }

        if(!is_array($amenities))
        {
            if($amenities != '')
             $amenities = explode(',', $amenities);
            else
             $amenities = [];
        }

        $property_type_val   = [];
        $rooms_whereIn       = [];
        $room_type_val       = [];
        $rooms_address_where = [];

        $address      = str_replace([" ","%2C"], ["+",","], "$full_address");
        $geocode      = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.SERVER_MAP_KEY.'&address='.$address.'&sensor=false&libraries=places');
        $json         = json_decode($geocode);

        if(@$json->results)
        {
        foreach($json->results as $result)
        {
            foreach($result->address_components as $addressPart)
            {
                if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $city1 = $addressPart->long_name;
                    $rooms_address_where['rooms_address.city'] = $city1;
                }
                if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $state = $addressPart->long_name;
                    $rooms_address_where['rooms_address.state'] = $state;
                }
                if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $country = $addressPart->short_name;
                    $rooms_address_where['rooms_address.country'] = $country;
                }
            }
        }
        }

        if($map_details != '')
        {
            $map_detail =   explode('~', $map_details);
            $zoom       =   $map_detail[0];
            $bounds     =   $map_detail[1];
            $minLat     =   $map_detail[2];
            $minLong    =   $map_detail[3];
            $maxLat     =   $map_detail[4];
            $maxLong    =   $map_detail[5];
            $cLat       =   $map_detail[6];
            $cLong      =   $map_detail[7];
        }
        else
        {
            if(@$json->{'results'})
            {
                $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

                $minLat = $data['lat']-0.30;
                $maxLat = $data['lat']+0.30;
                $minLong = $data['long']-0.30;
                $maxLong = $data['long']+0.30;
            }
            else
            {
                $data['lat'] = 0;
                $data['long'] = 0;

                $minLat = -1000;
                $maxLat = 1000;
                $minLong = -1000;
                $maxLong = 1000;
            }
        }
        $users_where['users.status']    = 'Active';

        $checkin  = date('Y-m-d', strtotime($checkin));
        $checkout = date('Y-m-d', strtotime($checkout));

        $days     = $this->get_days($checkin, $checkout);

        unset($days[count($days)-1]);

        $calendar_where['date'] = $days;

        $not_available_room_ids = Calendar::whereIn('date', $days)->whereStatus('Not available')->distinct()->lists('room_id');

        $rooms_where['rooms.accommodates'] = $guest;

        $rooms_where['rooms.status']       = 'Listed';

        if($bathrooms)
            $rooms_where['rooms.bathrooms'] = $bathrooms;

        if($bedrooms)
            $rooms_where['rooms.bedrooms']  = $bedrooms;

        if($beds)
            $rooms_where['rooms.beds']      = $beds;

        if(count($property_type))
        {
            foreach($property_type as $property_value)
                array_push($property_type_val, $property_value);

            $rooms_whereIn['rooms.property_type'] = $property_type_val;
        }

        if(count($room_type))
        {
            foreach($room_type as $room_value)
                array_push($room_type_val, $room_value);

            $rooms_whereIn['rooms.room_type'] = $room_type_val;
        }

        $currency_rate = Currency::where('code', Currency::find(1)->session_code)->first()->rate;

        $max_price_check = $this->payment_helper->currency_convert('', 'USD', $max_price);

        $rooms = Rooms::with(['rooms_address' => function($query) use($minLat, $maxLat, $minLong, $maxLong) { },
                            'rooms_price' => function($query) use($min_price, $max_price) {
                                $query->with('currency');
                            },
                            'users' => function($query) use($users_where) {
                                $query->with('profile_picture')
                                      ->where($users_where);
                            },
                            'saved_wishlists' => function($query) {
                                $query->where('user_id', @Auth::user()->user()->id);
                            }])
                            ->whereHas('rooms_address', function($query) use($minLat, $maxLat, $minLong, $maxLong) {
                                 $query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
                            })
                            ->whereHas('rooms_price', function($query) use($min_price, $max_price, $currency_rate, $max_price_check) {
                                    $query->join('currency', 'currency.code', '=', 'rooms_price.currency_code');
                                    if($max_price_check >= 750)
                                    {
                                        $query->whereRaw('((night / currency.rate) * '.$currency_rate.') >= '.$min_price);
                                    }
                                    else
                                    {
                                        $query->whereRaw('((night / currency.rate) * '.$currency_rate.') >= '.$min_price.' and ((night / currency.rate) * '.$currency_rate.') <= '.$max_price);
                                    }
                            })
                            ->whereHas('users', function($query) use($users_where) {
                                $query->where($users_where);
                            })
                            ->whereNotIn('id', $not_available_room_ids);

        if($rooms_where)
        {
            foreach($rooms_where as $row=>$value)
            {
                if($row == 'rooms.accommodates' || $row == 'rooms.bathrooms' || $row == 'rooms.bedrooms' || $row == 'rooms.beds')
                    $operator = '>=';
                else
                    $operator = '=';

                if($value == '')
                    $value = 0;

                $rooms = $rooms->where($row, $operator, $value);
            }
        }

        if($rooms_whereIn)
        {
            foreach($rooms_whereIn as $row_rooms_whereIn => $value_rooms_whereIn)
                $rooms = $rooms->whereIn($row_rooms_whereIn, array_values($value_rooms_whereIn));
        }

        if(count($amenities))
        {
            foreach($amenities as $amenities_value)
                $rooms = $rooms->whereRaw('find_in_set('.$amenities_value.', amenities)');
        }

        $rooms = $rooms->paginate(18)->toJson();
        echo $rooms;
        /* $rooms = $rooms->get();
        $queries = DB::getQueryLog();
        // $last_query = end($queries);
        print_r($queries);exit;*/
    }

    function places(Request $request)
    {
        $full_address  = $request->input('location');
        $map_details   = $request->input('map_details');
        $types = $request->input('types');

        $place_where = [];

        $address      = str_replace([" ","%2C"], ["+",","], "$full_address");
        $geocode      = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.SERVER_MAP_KEY.'&address='.$address.'&sensor=false&libraries=places');
        $json         = json_decode($geocode);

        if(@$json->results)
        {
        foreach($json->results as $result)
        {
            foreach($result->address_components as $addressPart)
            {
                if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $city1 = $addressPart->long_name;
                    $place_where['rooms_address.city'] = $city1;
                }
                if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $state = $addressPart->long_name;
                    $place_where['rooms_address.state'] = $state;
                }
                if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))
                {
                    $country = $addressPart->short_name;
                    $place_where['rooms_address.country'] = $country;
                }
            }
        }
        }

        if($map_details != '')
        {
            $map_detail =   explode('~', $map_details);
            $zoom       =   $map_detail[0];
            $bounds     =   $map_detail[1];
            $minLat     =   $map_detail[2];
            $minLong    =   $map_detail[3];
            $maxLat     =   $map_detail[4];
            $maxLong    =   $map_detail[5];
            $cLat       =   $map_detail[6];
            $cLong      =   $map_detail[7];
        }
        else
        {
            if(@$json->{'results'})
            {
                $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

                $minLat = $data['lat']-0.30;
                $maxLat = $data['lat']+0.30;
                $minLong = $data['long']-0.30;
                $maxLong = $data['long']+0.30;
            }
            else
            {
                $data['lat'] = 0;
                $data['long'] = 0;

                // $minLat = -1000;
                // $maxLat = 1000;
                // $minLong = -1000;
                // $maxLong = 1000;
                $minLat = -51;
                $maxLat = 90;
                $minLong = -180;
                $maxLong = 180;
            }
        }

        if($types) {
            if($types != 'empty') {
                $where_types = rtrim($types, ',');
                $where_types = explode(',', $where_types);
            }
        }

/*'users' => function($query) use($users_where) {
                                $query->with('profile_picture')
                                      ->where($users_where);
                            },*/
        $places = Places::with(['reviews' => function($query){
            $query->with(['users_from'=>function($query){
                $query->with('profile_picture');
            }]);
        }])->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");


        if(@$where_types)
         $places = $places->whereIn('type', $where_types);

        if($types != 'empty')
        {
            if( $minLat = "-1000" && $maxLat = "1000" )
                echo $places->take(500)->get();
            else
                echo $places->get();
        }
        else
            echo 'false';

        // $queries = DB::getQueryLog();
        // print_r($queries);exit;
    }

    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;

        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;
        }

        return $aDays;
    }

    /**
     * Get rooms photo details
     *
     * @param  array $request       Input values
     * @return json $rooms_photo    Rooms Photos Details
     */
    public function rooms_photos(Request $request)
    {
        $rooms_id  = $request->rooms_id;
        $roomsDetails =  RoomsPhotos::where('room_id', $request->rooms_id)->get();

        return json_encode($roomsDetails);
    }

}
