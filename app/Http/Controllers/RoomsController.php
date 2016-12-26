<?php

/**
 * Rooms Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EmailController;
use App\Models\PropertyType;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\BedType;
use App\Models\RoomsStepsStatus;
use App\Models\Country;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\ReligiousAmenities;
use App\Models\ReligiousAmenitiesType;
use App\Models\RoomsPhotos;
use App\Models\RoomsPrice;
use App\Models\RoomsDescription;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Reservation;
use App\Models\Messages;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Auth;
use DB;
use DateTime;
use Session;

class RoomsController extends Controller
{
    protected $payment_helper; // Global variable for Helpers instance

    /**
     * @var Helpers
     */
    protected $helper;

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Load Your Listings View
     *
     * @return your listings view file
     */
    public function index()
    {
        $data['listed_result']   = Rooms::user()->where('status', 'Listed')->get();

        $data['unlisted_result'] = Rooms::user()->where(function ($query){
            $query->where('status','Unlisted')->orWhereNull('status');
        })->get();

        return view('rooms.listings', $data);
    }

    /**
     * Load List Your Space First Page
     *
     * @return list your space first view file
     */
    public function new_room()
    {

        $data['property_type'] = PropertyType::active_all();

        $data['room_type']     = RoomType::active_all();

        return view('list_your_space.new', $data);
    }

    /**
     * Create a new Room
     *
     * @param array $request    Post values from List Your Space first page
     * @return redirect     to manage listing
     */
    public function create(Request $request)
    {
        $user_id = Auth::user()->user()->id;

        $rooms = Rooms::where('user_id', $user_id)->whereNull('status')->first();

        if (!$rooms)
        {
            $rooms = new Rooms;

            $rooms->user_id       = $user_id;
            /*$rooms->sub_name      = RoomType::find($request->hosting['room_type'])->name.' in '.$request->hosting['city'];
            $rooms->property_type = $request->hosting['property_type_id'];
            $rooms->room_type     = $request->hosting['room_type'];
            $rooms->accommodates  = $request->hosting['person_capacity'];*/

            $rooms->property_type = 1;
            $rooms->room_type     = 1;
            $rooms->accommodates  = 1;
            $rooms->started  = 'Yes';

            $rooms->save(); // Store data to rooms Table

            $rooms_address = new RoomsAddress;

            $rooms_address->room_id        = $rooms->id;
            /*$rooms_address->address_line_1 = $request->hosting['route'];
            $rooms_address->city           = $request->hosting['city'];
            $rooms_address->state          = $request->hosting['state'];
            $rooms_address->postal_code    = $request->hosting['postal_code'];
            $rooms_address->latitude       = $request->hosting['latitude'];
            $rooms_address->longitude      = $request->hosting['longitude'];
            $rooms_address->country        = $request->hosting['country'];*/

            $rooms_address->save(); // Store data to rooms_address Table

            $rooms_price = new RoomsPrice;

            $rooms_price->room_id       = $rooms->id;
            $rooms_price->currency_code = Session::get('currency');

            $rooms_price->save();   // Store data to rooms_price table

            $rooms_status = new RoomsStepsStatus;

            $rooms_status->room_id = $rooms->id;

            $rooms_status->save();  // Store data to rooms_steps_status table

            $rooms_description = new RoomsDescription;

            $rooms_description->room_id = $rooms->id;

            $rooms_description->save(); // Store data to rooms_description table
        }

        return redirect('manage-listing/'.$rooms->id.'/basics');
    }

    /**
     * Manage Listing
     *
     * @param array $request    Post values from List Your Space first page
     * @param array $calendar   Instance of CalendarController
     * @return list your space main view file
     */
    public function manage_listing(Request $request, CalendarController $calendar)
    {
        $data['property_type']  = PropertyType::dropdown();
        $data['room_type']      = RoomType::dropdown();
        $data['bed_type']       = BedType::active_all();
        $data['amenities']      = Amenities::active_all();
        $data['amenities_type'] = AmenitiesType::active_all();

        $data['religious_amenities']      = ReligiousAmenities::active_all();
        $data['religious_amenities_types']= ReligiousAmenitiesType::active_all();

        $data['room_id']        = $request->id;
        $data['room_step']      = $request->page;    // It will get correct view file based on page name

        $data['result']         = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not

        $data['rooms_status']   = RoomsStepsStatus::where('room_id',$request->id)->first();

        if(count($data['result']) == 0)
            abort('404');

        $data['calendar']       = $calendar->generate($request->id);

        $data['prev_amenities'] = explode(',', $data['result']->amenities);
        $data['prev_religious_amenities'] = explode(',', $data['result']->religious_amenities);
        $data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);

        if (Auth::user()->user()->status != 'Active')
        {
            $this->helper->flash_message('danger', trans('messages.new.force_confirm_email'));
        }

        return view('list_your_space.main', $data);
    }

    /**
     * Ajax Manage Listing, while you click steps from sidebar, it will call
     *
     * @param array $request    Post values from List Your Space first page
     * @param array $calendar   Instance of CalendarController
     * @return list your space steps view file
     */
    public function ajax_manage_listing(Request $request, CalendarController $calendar)
    {
        $data['property_type']  = PropertyType::dropdown();
        $data['room_type']      = RoomType::dropdown();
        $data['bed_type']       = BedType::active_all();
        $data['amenities']      = Amenities::active_all();
        $data['amenities_type'] = AmenitiesType::active_all();

        $data['religious_amenities']      = ReligiousAmenities::active_all();
        $data['religious_amenities_types']= ReligiousAmenitiesType::active_all();

        $data['room_id']        = $request->id;
        $data['room_step']      = $request->page;    // It will get correct view file based on page name

        $data['result']         = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not

        $data['prev_amenities'] = explode(',', $data['result']->amenities);

        $data['prev_religious_amenities'] = explode(',', $data['result']->religious_amenities);
        $data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);

        $data['rooms_status']   = RoomsStepsStatus::where('room_id',$request->id)->first();

        if($data['result']->status != NULL && $request->page == 'calendar')
        {
            $data_calendar     = @json_decode($request['data']);
            $year              = @$data_calendar->year;
            $month             = @$data_calendar->month;
            //$data['room_step'] = 'edit_calendar';
            $data['calendar']  = $calendar->generate($request->id, $year, $month);
        }else{
            if($request['data']){
                $data_calendar     = @json_decode($request['data']);
                $year              = @$data_calendar->year;
                $month             = @$data_calendar->month;
                $data['calendar']  = $calendar->generate($request->id, $year, $month);
            }else{
                $data['calendar'] = $calendar->generate($request->id);
            }
        }

        return view('list_your_space.'.$data['room_step'], $data);
    }

    /**
     * Ajax List Your Space Header
     *
     * @param array $request    Post values from List Your Space first page
     * @return list your space header view file
     */
    public function ajax_header(Request $request)
    {
        $data['room_id']   = $request->id;
        $data['room_step'] = $request->page;

        $data['result']    = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not

        return view('list_your_space.header', $data);
    }

    /**
     * Ajax List Your Space Update Rooms Values
     *
     * @param array $request    Post values from List Your Space first page
     * @return json success, steps_count
     */
    public function update_rooms(Request $request, EmailController $email_controller)
    {

        $data  = $request;

        $data  = json_decode($data['data']); // AngularJS data decoding

        $rooms = Rooms::find($request->id); // Where condition for Update

        $email = '';

        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update

            if($key == 'booking_type')
                $rooms->$key = (!empty($value)) ? $value : NULL;

            if($key == 'room_type')
                $rooms->sub_name = RoomType::single_field($value, 'name').' in '.RoomsAddress::single_field($request->id, 'city');

            if($key == 'status' && $value == 'Listed')
                $email = 'Listed';

            if($key == 'status' && $value == 'Unlisted')
                $email = 'Unlisted';
        }

        $rooms->save(); // Save rooms data to rooms table

        if($email == 'Listed')
            $email_controller->listed($request->id);

        if($email == 'Unlisted')
            $email_controller->unlisted($request->id);

        $this->update_status($request->id); // This function for update steps count in rooms_steps_count table

        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_completed]);
    }

    /**
     * Update List Your Space Steps Count, It will calling from ajax update functions
     *
     * @param int $id    Room Id
     * @return true
     */
    public function update_status($id)
    {
        $result_rooms = Rooms::whereId($id)->first();

        $rooms_status = RoomsStepsStatus::find($id);

        if($result_rooms->rooms_description['details_later'] == 1)
            $rooms_status->details = 1;
        else if($result_rooms->rooms_description['space'] != "" || $result_rooms->rooms_description['access'] != "" || $result_rooms->rooms_description['interaction'] != "" || $result_rooms->rooms_description['notes'] != "" || $result_rooms->rooms_description['house_rules'] != "" || $result_rooms->rooms_description['neighborhood_overview'] != "" || $result_rooms->rooms_description['transit'] != "")
            $rooms_status->details = 1;
        else
            $rooms_status->details = 0;

        if($result_rooms->booking_type != NULL)
            $rooms_status->booking = 1;
        else
            $rooms_status->booking = 0;

        if($result_rooms->cancel_policy != "")
            $rooms_status->terms = 1;
        else
            $rooms_status->terms = 0;

        if($result_rooms->name != '' && $result_rooms->summary != '')
            $rooms_status->description = 1;
        else
            $rooms_status->description = 0;

        //if($result_rooms->bedrooms != '' && $result_rooms->beds != '' && $result_rooms->bathrooms != '' && $result_rooms->bed_type != '')
        if(($result_rooms->bedrooms != '' || $result_rooms->bedrooms == '0') && ($result_rooms->beds != '' || $result_rooms->beds == '0') && ($result_rooms->bathrooms != '' || $result_rooms->bathrooms == '0') && ($result_rooms->bed_type != '' || $result_rooms->bed_type == '0'))
            $rooms_status->basics = 1;
        else
            $rooms_status->basics = 0;

        $photos_count = RoomsPhotos::where('room_id', $id)->count();

        if($photos_count != 0)
            $rooms_status->photos = 1;
        else
            $rooms_status->photos = 0;

        $price = RoomsPrice::find($id);

        if($price != NULL)
        {
        if($price->night != 0)
            $rooms_status->pricing = 1;
        else
            $rooms_status->pricing = 0;
        }

        if($result_rooms->calendar_type != NULL)
            $rooms_status->calendar = 1;

        if($result_rooms->amenities != NULL && $result_rooms->amenities != '')
            $rooms_status->amenities = 1;
        else
            $rooms_status->amenities = 0;

        $rooms_status->save(); // Update Rooms Steps Count

        return true;
    }

    /**
     * Load List Your Space Address Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */
    public function enter_address(Request $request)
    {
        $data_result['room_id']   = $request->id;
        $data_result['room_step'] = $request->page;
        $data_result['country']   = Country::all()->lists('long_name','short_name');

        $data  = $request;

        $data  = json_decode($data['data']); // AngularJS data decoding

        if($data->country != ''){
            $data->country_name = Country::where('short_name', $data->country)->first()->long_name;
        }

        $data_result['result'] = $data;

        // $data['result']    = RoomsAddress::where('room_id',$request->id)->first();

        return view('list_your_space.enter_address', $data_result);
    }

    /**
     * Load List Your Space Address Location Not Found Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */
    public function location_not_found(Request $request)
    {
        $data  = $request;

        $data  = json_decode($data['data']); // AngularJS data decoding

        /*$rooms = RoomsAddress::find($request->id); // Where condition for Update
        
        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update
        }

        $rooms->save();*/

        // $data_result['result'] = RoomsAddress::find($request->id);
        $data->country_name = Country::where('short_name', $data->country)->first()->long_name;

        $data_result['result'] = $data;

        return view('list_your_space.location_not_found', $data_result);
    }

    /**
     * Load List Your Space Verify Location Popup
     *
     * @param array $request    Input values
     * @return verify_location view file
     */
    public function verify_location(Request $request)
    {
        $data  = $request;

        $data  = json_decode($data['data']); // AngularJS data decoding

        // $data['result'] = RoomsAddress::find($request->id);
        $data->country_name = Country::where('short_name', $data->country)->first()->long_name;
        $data_result['result'] = $data;

        return view('list_your_space.verify_location', $data_result);
    }

    /**
     * List Your Space Address Data
     *
     * @param array $request    Input values
     * @return json rooms_address result
     */
    public function finish_address(Request $request)
    {
        $data  = $request;

        $data  = json_decode($data['data']); // AngularJS data decoding

        $rooms = RoomsAddress::find($request->id); // Where condition for Update

        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update
        }

        $rooms->save();

        $rooms_status = RoomsStepsStatus::find($request->id);

        $rooms_status->location = 1;

        $rooms_status->save();

        $data_result = RoomsAddress::find($request->id);

        return json_encode($data_result);
    }

    /**
     * Ajax Update List Your Space Amenities
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_amenities(Request $request)
    {
        $rooms = Rooms::find($request->id);

        $rooms->amenities = rtrim($request->data,',');

        $rooms->religious_amenities = rtrim($request->religious_data, ',');

        $religious_amenities_extra_data_posted = json_decode($request->religious_extra_data);
        $religious_amenities_posted = explode(',',$rooms->religious_amenities);
        $religious_amenities_extra_data = array();
        foreach($religious_amenities_extra_data_posted as $k => $v){
            if(in_array($k , $religious_amenities_posted)){
                $religious_amenities_extra_data[$k] = $v;
            }
        }
        $rooms->religious_amenities_extra_data = serialize($religious_amenities_extra_data);

        $rooms->save();

        $this->update_status($request->id); // This function for update steps count in rooms_steps_count table

        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_completed]);

        //return json_encode(['success'=>'true']);
    }

    /**
     * Ajax List Your Space Add Photos, it will upload multiple files
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function add_photos(Request $request)
    {
        if(isset($_FILES["photos"]["name"]))
        {
            foreach($_FILES["photos"]["error"] as $key=>$error)
            {
                $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $name = time().'_'.$name;

                $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->id;

                if(!file_exists($filename))
                {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->id, 0777, true);
                }

                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')
                {
                if(move_uploaded_file($tmp_name, "images/rooms/".$request->id."/".$name))
                {
                    $photos          = new RoomsPhotos;
                    $photos->room_id = $request->id;
                    $photos->name    = $name;
                    $photos->save();

                    $this->update_status($request->id);
                }
                }
                else
                {
                    return json_encode(['error_title' => ' Photo Error', 'error_description' => 'Sorry, something went wrong. Please try again.']);
                }
            }

            $photos_featured = RoomsPhotos::where('room_id',$request->id)->where('featured','Yes');

            if($photos_featured->count() == 0)
            {
                $photos = RoomsPhotos::where('room_id',$request->id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }

            $result = RoomsPhotos::where('room_id',$request->id)->get();
            return json_encode($result);
        }
    }

    /**
     * Ajax List Your Space Delete Photo
     *
     * @param array $request    Input values
     * @return json success, steps_count
     */
    public function delete_photo(Request $request)
    {
        $photos          = RoomsPhotos::find($request->photo_id)->delete();

        $photos_featured = RoomsPhotos::where('room_id',$request->id)->where('featured','Yes');

        if($photos_featured->count() == 0)
        {
            $photos_featured = RoomsPhotos::where('room_id',$request->id);

            if($photos_featured->count() != 0)
            {
                $photos = RoomsPhotos::where('room_id',$request->id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }
        }

        $rooms = Rooms::find($request->id);

        $this->update_status($request->id);

        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_completed]);
    }

    /**
     * Ajax List Your Space Photos List
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function photos_list(Request $request)
    {
        $photos = RoomsPhotos::where('room_id', $request->id)->get();

        return json_encode($photos);
    }

    /**
     * Ajax List Your Space Photos Highlights
     *
     * @param array $request    Input values
     * @return json success
     */
    public function photo_highlights(Request $request)
    {
        $photos = RoomsPhotos::find($request->photo_id);

        $photos->highlights = $request->data;

        $photos->save();

        return json_encode(['success'=>'true']);
    }

    /**
     * Load Rooms Detail View
     *
     * @param array $request    Input values
     * @return view rooms_detail
     */
    public function rooms_detail(Request $request)
    {
        $data['room_id']          = $request->id;

        $data['result']           = Rooms::find($request->id);

        if(count($data['result']) == 0)
        abort('404');

        $data['amenities']        = Amenities::selected($request->id);

        $data['safety_amenities'] = Amenities::selected_security($request->id);

        $data['religious_amenities'] = ReligiousAmenitiesType::get_selected_by_type($request->id);
        $data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);

        // $data['religous_amenities'] = Amenities::selected_type($request->id, 5); 
        // $data['religous_amenities_type'] = AmenitiesType::whereId(5)->first(); 

        $data['rooms_photos']     = RoomsPhotos::where('room_id', $request->id)->get();

        $rooms_address            = $data['result']->rooms_address;

        $latitude                 = $rooms_address->latitude;

        $longitude                = $rooms_address->longitude;

        if($request->checkin != '' && $request->checkout != '')
        {
            $data['checkin']         = date('m/d/Y', strtotime($request->checkin));
            $data['checkout']        = date('m/d/Y', strtotime($request->checkout));
            $data['guests']          = $request->guests;
        }
        else
        {
            $data['checkin'] = '';
            $data['checkout'] = '';
            $data['guests'] = '';
        }
        if($latitude != '' && $longitude != ''){
        $data['similar']          = Rooms::join('rooms_address', function($join) {
                                        $join->on('rooms.id', '=', 'rooms_address.room_id');
                                    })
                                    ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
                                    ->join('users', 'users.id', '=', 'rooms.user_id')
                                    ->having('distance', '<=', 30)
                                    ->where('rooms.id', '!=', $request->id)
                                    ->where('rooms.status', 'Listed')
                                    ->where('users.status', 'Active')
                                    ->get();
        }else{
            $data['similar'] = array();
        }
        $data['title']  =   $data['result']->name.' in '.$data['result']->rooms_address->city;
        return view('rooms.rooms_detail', $data);
    }

    /**
     * Load Rooms Detail Slider View
     *
     * @param array $request    Input values
     * @return view rooms_slider
     */
     public function rooms_slider(Request $request)
    {
        $data['room_id']      = $request->id;

        $data['result']       = Rooms::find($request->id);

        $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();

        return view('rooms.rooms_slider', $data);
    }

    /**
     * Ajax Update List Your Space Price
     *
     * @param array $request    Input values
     * @return json success, currency_symbol, steps_count
     */
    public function update_price(Request $request)
    {
        $data           = $request;

        $data           = json_decode($data['data']); // AngularJS data decoding

        $price          = RoomsPrice::find($request->id);

        $price->room_id = $request->id;

        foreach ($data as $key => $value)
        {
            $price->$key = $value;
        }

        $price->save();

        $this->update_status($request->id);

        return json_encode(['success'=>'true', 'currency_symbol' => $price->currency->original_symbol, 'steps_count' => $price->steps_completed]);
    }

    /**
     * Ajax List Your Space Steps Status
     *
     * @param array $request    Input values
     * @return json rooms_steps_status result
     */
    public function rooms_steps_status(Request $request)
    {
        return RoomsStepsStatus::find($request->id);
    }

    /**
     * Ajax Rooms Related Table Data
     *
     * @param array $request    Input values
     * @return json rooms, rooms_address, rooms_price, currency table results
     */
    public function rooms_data(Request $request)
    {
        $data           = Rooms::find($request->id);

        $rooms_address  = array_merge($data->toArray(),$data->rooms_address->toArray());

        $rooms_price    = array_merge($rooms_address,$data->rooms_price->toArray());

        $rooms_currency = array_merge($rooms_price,['symbol' => $data->rooms_price->currency->symbol ]);

        return json_encode($rooms_currency);
    }

    /**
     * Ajax Rooms Detail Calendar Dates Blocking
     *
     * @param array $request    Input values
     * @return json calendar results
     */
    public function rooms_calendar(Request $request)
    {
        // For coupon code destroy
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');

        $id     = $request->data;


        $result['not_avilable'] = Calendar::where('room_id', $id)->where('status','Not available')->get()->lists('date');

        $result['changed_price'] = Calendar::where('room_id', $id)->get()->lists('price','date');

        $result['price'] = RoomsPrice::where('room_id', $id)->get()->lists('night');

        $result['currency_symbol'] = Currency::find(1)->symbol;

        return json_encode($result);
    }

        public function rooms_calendar_alter(Request $request)
    {
        $id     = $request->data;

        $checkin                      = date('Y-m-d', strtotime($request->checkin));
        $date2                    = date('Y-m-d', strtotime($request->checkout));

        $checkout                      = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($date2 ) ) ));

        $booked_days = $this->get_days($checkin, $checkout);

        $result['not_avilable'] = Calendar::where('room_id', $id)->where('status','Not available')->whereNotIn('date',$booked_days)->get()->lists('date');

        $result['changed_price'] = Calendar::where('room_id', $id)->get()->lists('price','date');

        $result['price'] = RoomsPrice::where('room_id', $id)->get()->lists('night');

        $result['currency_symbol'] = Currency::find(1)->symbol;

        return json_encode($result);
    }
    /**
     * Ajax Rooms Detail Price Calculation while choosing date
     *
     * @param array $request    Input values
     * @return json price list
     */
    public function price_calculation(Request $request)
    {
       // For coupon code destroy
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');

        return $this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->guest_count ,'',$request->change_reservation);
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
     * Ajax Update List Your Space Description
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_description(Request $request)
    {
        $data           = $request;

        $data           = json_decode($data['data']); // AngularJS data decoding

        $price          = RoomsDescription::find($request->id);

        $price->room_id = $request->id;

        foreach ($data as $key => $value)
        {
            $price->$key = $value;
        }

        $price->save();

        $this->update_status($request->id);

        $rooms = Rooms::find($request->id); // Where condition for Update

        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_completed]);
    }

    /**
     * Ajax Update List Your Space Calendar Dates Price, Status
     *
     * @param array $request    Input values
     * @return empty
     */
    public function calendar_edit(Request $request)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $start_date = strtotime($start_date);

        $end_date   = date('Y-m-d', strtotime($request->end_date));
        $end_date   = strtotime($end_date);

        for ($i=$start_date; $i<=$end_date; $i+=86400)
        {
            $date = date("Y-m-d", $i);

            $data = [ 'room_id' => $request->id,
                      'price'   => ($request->price) ? $request->price : '0',
                      'status'  => "$request->status",
                      'notes'   => $request->notes,
                    ];

            Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $date], $data);
        }
    }

    /**
     * Contact Request send to Host
     *
     * @param array $request Input values
     * @return redirect to Rooms Detail page
     */
    public function contact_request(Request $request, EmailController $email_controller)
    {
        $data['price_list']       = json_decode($this->payment_helper->price_calculation($request->id, $request->message_checkin, $request->message_checkout, $request->message_guests));

        if(@$data['price_list']->status == 'Not available')
        {
            $this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
            return redirect('rooms/'.$request->id);
        }

        $rooms = Rooms::find($request->id);

        $reservation = new Reservation;

        $reservation->room_id          = $request->id;
        $reservation->host_id          = $rooms->user_id;
        $reservation->user_id          = Auth::user()->user()->id;
        $reservation->checkin          = $this->payment_helper->date_convert($request->message_checkin);
        $reservation->checkout         = $this->payment_helper->date_convert($request->message_checkout);
        $reservation->number_of_guests = $request->message_guests;
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
        $reservation->type             = 'contact';
        $reservation->country          = 'US';

        $reservation->save();

        $replacement = "[removed]";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

        $find = array($email_pattern, $phone_pattern);
        $replace = array($replacement, $replacement);

        $question = preg_replace($find, $replace, $request->question);
        $question = preg_replace($url_pattern, $replacement, $question);

        $message = new Messages;

        $message->room_id        = $request->id;
        $message->reservation_id = $reservation->id;
        $message->user_to        = Rooms::where(['id' => $request->id])->first()->user_id;
        $message->user_from      = Auth::user()->user()->id;
        $message->message        = $question;
        $message->message_type   = 9;
        $message->read           = 0;

        $message->save();

        $email_controller->inquiry($reservation->id, $question);

        $this->helper->flash_message('success', trans('messages.rooms.contact_request_has_sent',['first_name'=>$rooms->users->first_name])); // Call flash message function
        return redirect('rooms/'.$request->id);
    }
}
