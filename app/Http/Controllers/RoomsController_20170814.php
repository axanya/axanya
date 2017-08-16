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

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SmsController;
use App\Http\Helper\FacebookHelper;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\BedType;
use App\Models\Calendar;
use App\Models\Country;
use App\Models\Currency;
use App\Models\GuestGender;
use App\Models\Messages;
use App\Models\PropertyType;
use App\Models\ReligiousAmenities;
use App\Models\ReligiousAmenitiesType;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\RoomsBathroom;
use App\Models\RoomsBedOption;
use App\Models\RoomsBedroom;
use App\Models\RoomsDescription;
use App\Models\RoomsPhotos;
use App\Models\RoomsPolicies;
use App\Models\RoomsPrice;
use App\Models\RoomsStepsStatus;
use App\Models\RoomType;
use App\Models\User;
use App\Models\UsersPhoneNumber;
use App\Models\UsersVerification;
use Auth;
use DB;

// Facebook API
use Illuminate\Http\Request;
use Session;

class RoomsController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance
	private $fb;
	/**
	 * @var Helpers
	 */
	protected $helper;

	public function __construct(PaymentHelper $payment, FacebookHelper $fb) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
		$this->fb = $fb;
		if (Auth::user()->user()) {
			date_default_timezone_set(Auth::user()->user()->timezone);
		}
	}

	/**
	 * Load Your Listings View
	 *
	 * @return your listings view file
	 */
	public function index() {
		$data['listed_result'] = Rooms::user()->where('status', 'Listed')->get();

		$data['unlisted_result'] = Rooms::user()->where(function ($query) {
			$query->where('status', 'Unlisted')->orWhereNull('status');
		})->get();

		return view('rooms.listings', $data);
	}

	/**
	 * Load List Your Space First Page
	 *
	 * @return list your space first view file
	 */
	public function new_room() {

		$data['property_type'] = PropertyType::active_all();

		$data['room_type'] = RoomType::active_all();

		$data['guest_gender'] = GuestGender::active_all();

		return view('list_your_space.new', $data);
	}

	/**
	 * Create a new Room
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @return redirect     to manage listing
	 */
	public function create(Request $request) {
		if(Auth::admin()->check()) {
			return redirect('admin/dashboard');
		}
		if(Auth::user()->guest()) {
			return redirect('login');
		}

		$user_id = Auth::user()->user()->id;
		$rooms = Rooms::where('user_id', $user_id)->whereNull('status')->first();

		if (!$rooms) {
			$rooms = new Rooms;

			$rooms->user_id = $user_id;
			/*$rooms->sub_name      = RoomType::find($request->hosting['room_type'])->name.' in '.$request->hosting['city'];
				            $rooms->property_type = $request->hosting['property_type_id'];
				            $rooms->room_type     = $request->hosting['room_type'];
			*/

			$rooms->property_type = 1;
			$rooms->room_type = 1;
			$rooms->accommodates = 1;
			$rooms->guest_gender = 3;
			$rooms->started = 'Yes';

			$rooms->save(); // Store data to rooms Table

			$rooms_address = new RoomsAddress;
			$rooms_address->room_id = $rooms->id;
			/*$rooms_address->address_line_1 = $request->hosting['route'];
				            $rooms_address->city           = $request->hosting['city'];
				            $rooms_address->state          = $request->hosting['state'];
				            $rooms_address->postal_code    = $request->hosting['postal_code'];
				            $rooms_address->latitude       = $request->hosting['latitude'];
				            $rooms_address->longitude      = $request->hosting['longitude'];
			*/

			$rooms_address->save(); // Store data to rooms_address Table

			$rooms_price = new RoomsPrice;
			$rooms_price->room_id = $rooms->id;
			$rooms_price->currency_code = Session::get('currency');
			$rooms_price->save(); // Store data to rooms_price table

			$rooms_status = new RoomsStepsStatus;
			$rooms_status->room_id = $rooms->id;
			$rooms_status->save(); // Store data to rooms_steps_status table

			$rooms_description = new RoomsDescription;
			$rooms_description->room_id = $rooms->id;
			$rooms_description->save(); // Store data to rooms_description table
		}

		return redirect('manage-listing/' . $rooms->id . '/description');
	}

	/**
	 * Manage Listing
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @param array $calendar   Instance of CalendarController
	 * @return list your space main view file
	 */
	public function manage_listing(Request $request, CalendarController $calendar, SmsController $sms) {
		if ($request->page == 'verification') {
			global $app_mode;
			$data['app_mode'] = $app_mode;

			$rooms = Rooms::find($request->id);

			$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('long_name', 'ASC')->get();

			//if($data['result']['verification_status'] == '' || $data['result']['verification_status'] == 'Pending'){
			$user_id = Auth::user()->user()->id;

			$data['phone_info'] = $phone_info = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();

			if ($rooms->contact_number != '') {
				$data['selected_phone'] = $rooms->contact_number;
			} else {
				// $data['selected_phone'] = $phone_info->phone_code . ' ' . $phone_info->phone_number;
				$data['selected_phone'] = $phone_info->phone_number;
				if ($phone_info) {
					if ($phone_info->status == 'Pending') {
						$code = $this->get_rand_string(4);
						if ($phone_info->otp == '') {
							$up_phone_data['otp'] = $code;
							$up_phone_data['send_count'] = $phone_info->send_count + 1;
							UsersPhoneNumber::where(['id' => $phone_info->id])->update($up_phone_data);
						}

						$verification_status = 'Pending';
					} else {
						$rooms->contact_number = $data['selected_phone'];
						$verification_status = 'Verified';

					}
					$rooms->verification_status = $verification_status;
					$rooms->save();
				}
			}
			$this->update_status($request->id);
			// }
		}

		$data['property_type'] = PropertyType::dropdown();
		$data['room_type'] = RoomType::dropdown();
		$data['guest_gender'] = GuestGender::dropdown();
		$data['bed_type'] = BedType::active_all();
		$data['amenities'] = Amenities::active_all();
		$data['amenities_type'] = AmenitiesType::active_all();
		$data['religious_amenities'] = ReligiousAmenities::active_all();
		$data['religious_amenities_types'] = ReligiousAmenitiesType::active_all();
		$data['room_id'] = $request->id;
		$data['room_step'] = $request->page; // It will get correct view file based on page name
		$data['result'] = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not
		$data['rooms_status'] = RoomsStepsStatus::where('room_id', $request->id)->first();
		$data['rooms_description'] = RoomsDescription::where('room_id', $request->id)->first();
		$data['rooms_policies'] = RoomsPolicies::where('room_id', $request->id)->first();

		if (count($data['result']) == 0) {
			abort('404');
		}

		$data['calendar'] = $calendar->generate($request->id);
		$data['prev_amenities'] = explode(',', $data['result']->amenities);
		$data['prev_religious_amenities'] = explode(',', $data['result']->religious_amenities);
		$data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);
		$data['get_currency'] = Currency::get();

		if (Auth::user()->user()->status != 'Active') {
			$this->helper->flash_message('danger', trans('messages.new.force_confirm_email'));
		}

		//24 hour time array
		$data['time_array'] = array(
			'00:00:00' => '12:00 AM',
			'01:00:00' => '01:00 AM',
			'02:00:00' => '02:00 AM',
			'03:00:00' => '03:00 AM',
			'04:00:00' => '04:00 AM',
			'05:00:00' => '05:00 AM',
			'06:00:00' => '06:00 AM',
			'07:00:00' => '07:00 AM',
			'08:00:00' => '08:00 AM',
			'09:00:00' => '09:00 AM',
			'10:00:00' => '10:00 AM',
			'11:00:00' => '11:00 AM',
			'12:00:00' => '12:00 PM',
			'13:00:00' => '01:00 PM',
			'14:00:00' => '02:00 PM',
			'15:00:00' => '03:00 PM',
			'16:00:00' => '04:00 PM',
			'17:00:00' => '05:00 PM',
			'18:00:00' => '06:00 PM',
			'19:00:00' => '07:00 PM',
			'20:00:00' => '08:00 PM',
			'21:00:00' => '09:00 PM',
			'22:00:00' => '10:00 PM',
			'23:00:00' => '11:00 PM'
		);

		return view('list_your_space.main', $data);
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
	 * Ajax Manage Listing, while you click steps from sidebar, it will call
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @param array $calendar   Instance of CalendarController
	 * @return list your space steps view file
	 */
	public function ajax_manage_listing(Request $request, CalendarController $calendar, SmsController $sms) {
		if ($request->page == 'verification') {
			global $app_mode;
			$data['app_mode'] = $app_mode;
			$rooms = Rooms::find($request->id);

			$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('long_name', 'ASC')->get();

			//if($data['result']['verification_status'] == '' || $data['result']['verification_status'] == 'Pending'){
			$user_id = Auth::user()->user()->id;

			$data['phone_info'] = $phone_info = UsersPhoneNumber::where(['user_id' => $user_id])->orderBy('id', 'DESC')->first();

			if ($rooms->contact_number != '') {
				$data['selected_phone'] = $rooms->contact_number;
			} else {
				$data['selected_phone'] = $phone_info->phone_number;
				if ($phone_info) {
					if ($phone_info->status == 'Pending') {
						$code = $this->get_rand_string(4);
						if ($phone_info->otp == '') {
							$up_phone_data['otp'] = $code;
							$up_phone_data['send_count'] = $phone_info->send_count + 1;
							UsersPhoneNumber::where(['id' => $phone_info->id])->update($up_phone_data);
						}
						$verification_status = 'Pending';
					} else {
						$rooms->contact_number = $data['selected_phone'];
						$verification_status = 'Verified';
					}
					$rooms->verification_status = $verification_status;
					$rooms->save();
				}
			}
			$this->update_status($request->id);
			// }
		}

		$data['property_type'] = PropertyType::dropdown();
		$data['room_type'] = RoomType::dropdown();
		$data['guest_gender'] = GuestGender::dropdown();
		$data['bed_type'] = BedType::active_all();
		$data['amenities'] = Amenities::active_all();
		$data['amenities_type'] = AmenitiesType::active_all();
		$data['religious_amenities'] = ReligiousAmenities::active_all();
		$data['religious_amenities_types'] = ReligiousAmenitiesType::active_all();
		$data['room_id'] = $request->id;
		$data['room_step'] = $request->page; // It will get correct view file based on page name
		$data['result'] = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not
		$data['prev_amenities'] = explode(',', $data['result']->amenities);
		$data['prev_religious_amenities'] = explode(',', $data['result']->religious_amenities);
		$data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);
		$data['rooms_status'] = RoomsStepsStatus::where('room_id', $request->id)->first();
		$data['rooms_policies'] = RoomsPolicies::where('room_id', $request->id)->first();
		if ($data['result']->status != NULL && $request->page == 'calendar') {
			$data_calendar = @json_decode($request['data']);
			$year = @$data_calendar->year;
			$month = @$data_calendar->month;
			//$data['room_step'] = 'edit_calendar';
			$data['calendar'] = $calendar->generate($request->id, $year, $month);
		} else {
			if ($request['data']) {
				$data_calendar = @json_decode($request['data']);
				$year = @$data_calendar->year;
				$month = @$data_calendar->month;
				$data['calendar'] = $calendar->generate($request->id, $year, $month);
			} else {
				$data['calendar'] = $calendar->generate($request->id);
			}
		}

		$data['get_currency'] = Currency::get();

		//24 hour time array
		$data['time_array'] = array('00:00:00' => '12:00 AM (midnight)', '01:00:00' => '01:00 AM',
			'02:00:00' => '02:00 AM', '03:00:00' => '03:00 AM',
			'04:00:00' => '04:00 AM', '05:00:00' => '05:00 AM',
			'06:00:00' => '06:00 AM', '07:00:00' => '07:00 AM',
			'08:00:00' => '08:00 AM', '09:00:00' => '09:00 AM',
			'10:00:00' => '10:00 AM', '11:00:00' => '11:00 AM',
			'12:00:00' => '12:00 PM (noon)', '13:00:00' => '01:00 PM',
			'14:00:00' => '02:00 PM', '15:00:00' => '03:00 PM',
			'16:00:00' => '04:00 PM', '17:00:00' => '05:00 PM',
			'18:00:00' => '06:00 PM', '19:00:00' => '07:00 PM',
			'20:00:00' => '08:00 PM', '21:00:00' => '09:00 PM',
			'22:00:00' => '10:00 PM', '23:00:00' => '11:00 PM');

		return view('list_your_space.' . $data['room_step'], $data);
	}

	public function mobile_verification(Request $request, SmsController $sms) {
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
				$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'new_code' => $code];
			} else {
				$response = ['success' => 'false', 'steps_count' => $rooms->steps_completed, 'msg' => $res['msg']];
			}

		} else if ($request->type == 'verify') {
			$phone_info = UsersPhoneNumber::find($request->phone_id);

			$prev_code = $phone_info->otp;
			$new_code = $request->verification_code;
			if ($new_code == $prev_code) {

				$phone_info->status = 'Confirmed';
				$phone_info->save();

				$rooms = Rooms::find($request->id);
				// $rooms->contact_number = $phone_info->phone_code . ' ' . $phone_info->phone_number;
				$rooms->contact_number = $phone_info->phone_number;
				$rooms->verification_status = 'Verified';
				$rooms->save();

				$this->update_status($request->id);

				UsersVerification::where(['user_id' => $user_id])->update(['phone' => 'yes']);

				$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'msg' => "Verification done successfully."];
				return json_encode($response);
			} else {
				$response = ['success' => 'false', 'steps_count' => $rooms->steps_completed, 'msg' => "Invalid otp, Please try another code or resend otp."];
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

						if ($app_mode == 'production') {
							$code = '';
						}

						$rooms = Rooms::find($request->id);
						$rooms->contact_number = '';
						$rooms->verification_status = 'Pending';
						$rooms->save();

						$this->update_status($request->id);

						UsersVerification::where(['user_id' => $user_id])->update(['phone' => 'no']);

						$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'new_code' => $code];
					} else {
						$response = ['success' => 'false', 'msg' => $res['msg']];
					}

					return json_encode($response);
					/*$response = ['success' => 'false', 'steps_count' => $rooms->steps_completed, 'msg' => "This phone number already exist. Please try another."];
					return json_encode($response);*/
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

						UsersPhoneNumber::insert($insert);

						if ($app_mode == 'production') {
							$code = '';
						}

						$rooms = Rooms::find($request->id);
						$rooms->contact_number = '';
						$rooms->verification_status = 'Pending';
						$rooms->save();

						$this->update_status($request->id);

						$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'new_code' => $code];
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
	 * Ajax List Your Space Header
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @return list your space header view file
	 */
	public function ajax_header(Request $request) {
		$data['room_id'] = $request->id;
		$data['room_step'] = $request->page;

		$data['result'] = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not

		return view('list_your_space.header', $data);
	}

	/**
	 * Ajax List Your Space Update Rooms Values
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @return json success, steps_count
	 */
	public function get_bedroom_details(Request $request) {
		$total_bedrooms = [];
		$data = $request;
		$data = json_decode($data['data']);
		$rooms = Rooms::find($request->id);
		$total_bedrooms = RoomsBedroom::get_bedroom_details($request->id);
		$total_bathrooms = RoomsBathroom::where(['room_id' => $request->id])->get();
		$steps = RoomsStepsStatus::select('basics')->where(['room_id' => $request->id])->first();
		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed, 'total_bedrooms' => $total_bedrooms, 'total_bathrooms' => $total_bathrooms, 'basics' => $steps->basics]);
	}

	/**
	 * Ajax List Your Space Update Rooms Values
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @return json success, steps_count
	 */
	public function get_rooms_status(Request $request) {
		$total_bedrooms = [];
		$data = $request;
		$data = json_decode($data['data']); // AngularJS data decoding
		$rooms = Rooms::find($request->id); // Where condition for Update
		$rooms_status = RoomsStepsStatus::where('room_id', $request->id)->first();
		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed, 'rooms_status' => $rooms_status]);
	}

	/**
	 * Ajax List Your Space Update Rooms Values
	 *
	 * @param array $request    Post values from List Your Space first page
	 * @return json success, steps_count
	 */
	public function update_rooms(Request $request, EmailController $email_controller) {
		$response = [];
		$data = $request;
		$data = json_decode($data['data']); // AngularJS data decoding
		$rooms = Rooms::find($request->id); // Where condition for Update

		$email = '';
		foreach ($data as $key => $value) {
			$rooms->$key = str_replace("'", "\'", $value); // Dynamic Update

			if ($key == 'booking_type') {
				$rooms->$key = (!empty($value)) ? $value : NULL;
			}

			if ($key == 'room_type') {
				$rooms->sub_name = RoomType::single_field($value, 'name') . ' in ' . RoomsAddress::single_field($request->id, 'city');
			}

			if ($key == 'status' && $value == 'Listed') {
				$email = 'Listed';
			}

			if ($key == 'status' && $value == 'Unlisted') {
				$email = 'Unlisted';
			}

			//if type is bedrooms
			if ($key == 'bedrooms') {
				$prev_count = RoomsBedroom::count($request->id);

				$insert_data = [];
				if ($prev_count < $value) {
					$bedroom_count = $value - $prev_count;
					for ($i = 0; $i < $bedroom_count; $i++) {
						$insert = array('room_id' => $request->id);
						$insert_data[] = $insert;
					}
					RoomsBedroom::insert($insert_data);
				} else {
					$bedroom_count = $prev_count - $value;
					RoomsBedroom::delete_rows($request->id, $bedroom_count);
				}

				$total_bedrooms = RoomsBedroom::get_bedroom_details($request->id);

				$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'total_bedrooms' => $total_bedrooms];
			}

			//if type is bathrooms
			if ($key == 'bathrooms') {

				$prev_count = RoomsBathroom::count($request->id);

				$insert_data = [];
				if ($prev_count < $value) {
					$bedroom_count = $value - $prev_count;
					for ($i = 0; $i < $bedroom_count; $i++) {
						$insert = array('room_id' => $request->id,
							'bathroom_details' => '',
							'type' => '');
						$insert_data[] = $insert;
					}
					RoomsBathroom::insert($insert_data);
				} else {
					$bathroom_count = $prev_count - $value;
					RoomsBathroom::delete_rows($request->id, $bathroom_count);
				}

				$total_bathrooms = RoomsBathroom::get_bedroom_details($request->id);

				$response = ['success' => 'true', 'steps_count' => $rooms->steps_completed, 'total_bathrooms' => $total_bathrooms];
			}
		}

		//$rooms->calendar_length = 18;
		$rooms->save(); // Save rooms data to rooms table

		if ($email == 'Listed') {
			$email_controller->listed($request->id);
		}

		if ($email == 'Unlisted') {
			$email_controller->unlisted($request->id);
		}

		$this->update_status($request->id); // This function for update steps count in rooms_steps_count table

		if (count($response)) {
			return json_encode($response);
		} else {
			return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
		}

	}

	/**
	 * Load List Your Space Address Popup
	 *
	 * @param array $request    Input values
	 * @return enter_address view file
	 */
	public function update_rooms_bathroom(Request $request) {
		$data_result['room_id'] = $request->id;
		$data = $request;

		$rooms = Rooms::find($request->id); // Where condition for Update

		$data = json_decode($data['data']); // AngularJS data decoding
		//print_r($data);die;
		if (isset($data->bathrooms_details)) {
			$update['bathroom_details'] = $data->bathrooms_details;

			RoomsBathroom::where(['id' => $data->data_id, 'room_id' => $request->id])
				->update($update);

		} else if (isset($data->bathrooms_type)) {
			$update['type'] = $data->bathrooms_type;

			RoomsBathroom::where(['id' => $data->data_id, 'room_id' => $request->id])
				->update($update);
		}

		$this->update_status($request->id);

		// $data['result']    = RoomsAddress::where('room_id',$request->id)->first();

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Load List Your Space Address Popup
	 *
	 * @param array $request    Input values
	 * @return enter_address view file
	 */
	public function update_rooms_policies(Request $request) {
		$data_result['room_id'] = $request->id;
		$data = $request;

		$rooms = Rooms::find($request->id); // Where condition for Update

		$data = json_decode($data['data']); // AngularJS data decoding

		$policies = RoomsPolicies::find($request->id); // Where condition for Update

		if ($policies) {
			foreach ($data as $key => $value) {
				$policies->$key = $value;
			}
			$policies->save();
		} else {
			$insert_data = [];
			foreach ($data as $key => $value) {
				$insert['room_id'] = $request->id;
				$insert[$key] = $value;

				$insert_data[] = $insert;
			}
			RoomsPolicies::insert($insert_data);
		}

		$this->update_status($request->id);

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Update List Your Space Steps Count, It will calling from ajax update functions
	 *
	 * @param int $id    Room Id
	 * @return true
	 */
	public function update_status($id) {
		$result_rooms = Rooms::whereId($id)->first();

		$rooms_status = RoomsStepsStatus::find($id);

		if ($result_rooms->rooms_description['details_later'] == 1) {
			$rooms_status->details = 1;
		} else if ($result_rooms->rooms_description['space'] != "" || $result_rooms->rooms_description['access'] != "" || $result_rooms->rooms_description['interaction'] != "" || $result_rooms->rooms_description['notes'] != "" || $result_rooms->rooms_description['house_rules'] != "" || $result_rooms->rooms_description['neighborhood_overview'] != "" || $result_rooms->rooms_description['transit'] != "") {
			$rooms_status->details = 1;
		} else {
			$rooms_status->details = 0;
		}

		if ($result_rooms->booking_type != NULL) {
			$rooms_status->booking = 1;
		} else {
			$rooms_status->booking = 0;
		}

		$policies = RoomsPolicies::find($id);
		if ( $policies != "" && $policies->cancel_policy != '' && $policies->suitable_for_children != '' && $policies->suitable_for_infants != '' && $policies->suitable_for_pets != '' && $policies->smoking_allowed != '' && $policies->parties_allowed != '' ) {
			$rooms_status->terms = 1;
		} else {
			$rooms_status->terms = 0;
		}

		if ($result_rooms->name != '' && $result_rooms->summary != '') {
			$rooms_status->description = 1;
		} else {
			$rooms_status->description = 0;
		}

		//if($result_rooms->bedrooms != '' && $result_rooms->beds != '' && $result_rooms->bathrooms != '' && $result_rooms->bed_type != '')
		$total_bedrooms = RoomsBedroom::get_bedroom_count($id);
		$total_bathrooms = RoomsBathroom::count($id);

		if (($result_rooms->bathrooms != '' || $result_rooms->bathrooms == '0') && ($total_bathrooms > 0)) {
			$rooms_status->basics = 1;
		} else {
			$rooms_status->basics = 0;
		}

		$photos_count = RoomsPhotos::where('room_id', $id)->count();

		if ($photos_count != 0) {
			$rooms_status->photos = 1;
		} else {
			$rooms_status->photos = 0;
		}

		$price = RoomsPrice::find($id);

		if ($price != NULL) {
			if ($price->night != 0) {
				$rooms_status->pricing = 1;
			} else {
				$rooms_status->pricing = 0;
			}
		}

		if ($result_rooms->calendar_length != 0) {
			$rooms_status->calendar = 1;
		} else {
			$rooms_status->calendar = 0;
		}

		if ($result_rooms->is_referral == 'No' || $result_rooms->referral_code != '') {
			$rooms_status->referral = 1;
		} else {
			$rooms_status->referral = 0;
		}

		// $result_rooms->verification_status == 'Verified'
		if ($rooms_status->referral == 1 && Auth::user()->check() && Auth::user()->user()->users_verification->email == 'yes' && Auth::user()->user()->users_verification->phone == 'yes' && Auth::user()->user()->gender != '' && Auth::user()->user()->profile_picture->src != '' && Auth::user()->user()->profile_picture->src != url('/images/user_pic-225x225.png')) {
			$rooms_status->verification = 1;
		} else {
			$rooms_status->verification = 0;
		}

		if ($result_rooms->amenities != NULL && $result_rooms->amenities != '') {
			$rooms_status->amenities = 1;
		} else {
			$rooms_status->amenities = 0;
		}

		$check_location = RoomsAddress::where(['room_id' => $id])->first();
		if ($check_location->full_address != '') {
			$rooms_status->location = 1;
		} else {
			$rooms_status->location = 0;
		}

		$rooms_status->save();

		return true;
	}

	/**
	 * Load List Your Space Address Popup
	 *
	 * @param array $request    Input values
	 * @return enter_address view file
	 */
	public function enter_address(Request $request) {
		$data_result['room_id'] = $request->id;
		$data_result['room_step'] = $request->page;
		$data_result['country'] = Country::all()->lists('long_name', 'short_name');

		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		if ($data->country != '') {
			$data->country_name = Country::where('short_name', $data->country)->first()->long_name;
		}

		$data_result['result'] = $data;

		// $data['result']    = RoomsAddress::where('room_id',$request->id)->first();

		return view('list_your_space.enter_address', $data_result);
	}

	/**
	 * Load List Your Space bedroom Popup
	 *
	 * @param array $request    Input values
	 * @return enter_bedroom view file
	 */
	public function enter_bedroom(Request $request) {
		$data_result['room_id'] = $request->id;
		$data_result['room_step'] = $request->page;
		$data_result['country'] = Country::all()->lists('long_name', 'short_name');

		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding
		$bedroom_id = $data->data_id;
		$column_name = \App::getLocale() == 'iw' ? 'name_iw' : 'name';
		$data_result['bed_type'] = BedType::select('bedroom_id', 'bed_type.id', $column_name, 'quantity')->orderBy('priority', 'asc')
			->leftJoin('rooms_bed_option', function ($join) use ($bedroom_id) {
				$join->on('rooms_bed_option.bed_type_id', '=', 'bed_type.id')
					->where('rooms_bed_option.bedroom_id', '=', $bedroom_id);
			})->get();
		//  print_r($result);die;
		//        if($data->country != ''){
		//            $data->country_name = Country::where('short_name', $data->country)->first()->long_name;
		//        }
		$data_result['result'] = $data;

		// $data['result']    = RoomsAddress::where('room_id',$request->id)->first();

		return view('list_your_space.enter_bedroom', $data_result);
	}

	/**
	 * Load List Your Space bedroom Popup
	 *
	 * @param array $request Input values
	 * @return enter_bed_option view file
	 */
	public function enter_bed_option(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding
		$bedroom_id = $data->bedroom_id;
		RoomsBedOption::where(['bedroom_id' => $bedroom_id])->delete();
		$insert_data = [];
		foreach ($data as $key => $value) {
			if ($key != 'bedroom_id' && $value != 0) {
				$insert['bedroom_id'] = $bedroom_id;
				$insert['bed_type_id'] = $key;
				$insert['quantity'] = $value;

				$insert_data[] = $insert;
			}
		}
		RoomsBedOption::insert($insert_data);

		$this->update_status($request->id);
	}

	/**
	 * Load List Your Space Address Location Not Found Popup
	 *
	 * @param array $request    Input values
	 * @return enter_address view file
	 */
	public function location_not_found(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		/*$rooms = RoomsAddress::find($request->id); // Where condition for Update

			        foreach($data as $key=>$value)
			        {
			            $rooms->$key = $value;          // Dynamic Update
			        }

		*/

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
	public function verify_location(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

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
	public function finish_address(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		$rooms = RoomsAddress::find($request->id); // Where condition for Update

		foreach ($data as $key => $value) {
			$rooms->$key = $value; // Dynamic Update
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
	public function update_amenities(Request $request) {
		$rooms = Rooms::find($request->id);

		$rooms->amenities = rtrim($request->data, ',');

		$rooms->religious_amenities = rtrim($request->religious_data, ',');

		$religious_amenities_extra_data_posted = json_decode($request->religious_extra_data);
		$religious_amenities_posted = explode(',', $rooms->religious_amenities);
		$religious_amenities_extra_data = array();
		foreach ($religious_amenities_extra_data_posted as $k => $v) {
			if (in_array($k, $religious_amenities_posted)) {
				$religious_amenities_extra_data[$k] = $v;
			}
		}
		$rooms->religious_amenities_extra_data = serialize($religious_amenities_extra_data);

		$rooms->save();

		$this->update_status($request->id); // This function for update steps count in rooms_steps_count table

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);

		//return json_encode(['success'=>'true']);
	}

	/**
	 * Ajax List Your Space Add Photos, it will upload multiple files
	 *
	 * @param array $request    Input values
	 * @return json rooms_photos table result
	 */
	public function add_photos(Request $request) {
		if (isset($_FILES["photos"]["name"])) {
			foreach ($_FILES["photos"]["error"] as $key => $error) {
				$tmp_name = $_FILES["photos"]["tmp_name"][$key];

				$name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);

				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

				$name = time() . '_' . $name;

				$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $request->id;

				if (!file_exists($filename)) {
					mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $request->id, 0777, true);
				}

				if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
					if (move_uploaded_file($tmp_name, "images/rooms/" . $request->id . "/" . $name)) {
						$photos = new RoomsPhotos;
						$photos->room_id = $request->id;
						$photos->name = $name;
						$photos->save();

						$this->update_status($request->id);
					}
				} else {
					return json_encode(['error_title' => ' Photo Error', 'error_description' => 'Sorry, something went wrong. Please try again.']);
				}
			}

			$photos_featured = RoomsPhotos::where('room_id', $request->id)->where('featured', 'Yes');

			if ($photos_featured->count() == 0) {
				$photos = RoomsPhotos::where('room_id', $request->id)->first();
				$photos->featured = 'Yes';
				$photos->save();
			}

			$result = RoomsPhotos::where('room_id', $request->id)->get();
			return json_encode($result);
		}
	}

	/**
	 * Ajax List Your Space Delete Photo
	 *
	 * @param array $request    Input values
	 * @return json success, steps_count
	 */
	public function delete_photo(Request $request) {
		$photos = RoomsPhotos::find($request->photo_id)->delete();

		$photos_featured = RoomsPhotos::where('room_id', $request->id)->where('featured', 'Yes');

		if ($photos_featured->count() == 0) {
			$photos_featured = RoomsPhotos::where('room_id', $request->id);

			if ($photos_featured->count() != 0) {
				$photos = RoomsPhotos::where('room_id', $request->id)->first();
				$photos->featured = 'Yes';
				$photos->save();
			}
		}

		$rooms = Rooms::find($request->id);

		$this->update_status($request->id);

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Ajax List Your Space Photos List
	 *
	 * @param array $request    Input values
	 * @return json rooms_photos table result
	 */
	public function photos_list(Request $request) {
		$photos = RoomsPhotos::where('room_id', $request->id)->get();

		return json_encode($photos);
	}

	/**
	 * Ajax List Your Space Photos Highlights
	 *
	 * @param array $request    Input values
	 * @return json success
	 */
	public function photo_highlights(Request $request) {
		$photos = RoomsPhotos::find($request->photo_id);

		$photos->highlights = $request->data;

		$photos->save();

		return json_encode(['success' => 'true']);
	}

	/**
	 * Load Rooms Detail View
	 *
	 * @param array $request    Input values
	 * @return view rooms_detail
	 */
	public function rooms_detail(Request $request) {

		$data['room_id'] = $request->id;

		$data['result'] = Rooms::find($request->id);
		//get facebook mutual friends
		if (Auth::user()->user()) {
			$user_id = Auth::user()->user()->id;
			$guest_fb_account = UsersVerification::select('facebook_token', 'fb_id')->where(['user_id' => $user_id])->first();
			$host_fb_account = UsersVerification::select('facebook_token', 'fb_id')->where(['user_id' => $data['result']->users->id])->first();

			$guest_fb_token = $guest_fb_account->facebook_token;
			$host_fb_token = $host_fb_account->facebook_token;
			if ($guest_fb_token != '' && $host_fb_token != '') {
				//open connection
				$ch = curl_init();
				$url = 'https://graph.facebook.com/v2.9/' . $guest_fb_account->fb_id . '?fields=context.fields%28mutual_friends%29&access_token=' . $guest_fb_token;

				$headers = array(
					'Accept: application/json',
					'Content-Type: application/json',
				);

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_HEADER, 0);

				$body = '{}';
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				//execute post
				$result = curl_exec($ch);
				$result = json_decode($result);
				if (!$result->error) {
					$mutual_friends = $result->context->mutual_friends->data;

					$fb_ids = [];
					foreach ($mutual_friends as $key => $value) {
						$fb_ids[] = $value->id;
					}

					$fields = array('users.id', 'first_name', 'last_name', 'src', 'photo_source');
					$data['mutual_friends'] = User::join('users_verification', 'users_verification.user_id', '=', 'users.id')
						->leftJoin('profile_picture', 'profile_picture.user_id', '=', 'users.id')
						->whereIn('users_verification.fb_id', $fb_ids)
						->where('users_verification.fb_id', '!=', $guest_fb_account->fb_id)
						->where('users_verification.fb_id', '!=', $host_fb_account->fb_id)
						->get($fields);
				} else {
					$data['mutual_friends'] = [];
				}

				//close connection
				curl_close($ch);
			}
		}

		if (count($data['result']) == 0) {
			abort('404');
		}

		$beds = RoomsBedOption::select(DB::raw('SUM(rooms_bed_option.quantity) as beds'))
			->join('rooms_bedroom', 'rooms_bedroom.id', '=', 'rooms_bed_option.bedroom_id')
			->where(['rooms_bedroom.room_id' => $request->id])->get();

		$data['beds'] = $beds[0]->beds;
		$data['total_bedrooms'] = RoomsBedroom::get_bedroom_details($request->id);
		$data['total_bathrooms'] = RoomsBathroom::where(['room_id' => $request->id])->get();
		$data['amenities'] = Amenities::selected($request->id);
		$data['safety_amenities'] = Amenities::selected_security($request->id);
		$data['religious_amenities'] = ReligiousAmenitiesType::get_selected_by_type($request->id);
		$data['religious_amenities_extra_data'] = unserialize($data['result']->religious_amenities_extra_data);

		// $data['religous_amenities'] = Amenities::selected_type($request->id, 5);
		// $data['religous_amenities_type'] = AmenitiesType::whereId(5)->first();

		$data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();

		$rooms_address = $data['result']->rooms_address;

		$latitude = $rooms_address->latitude;
		$longitude = $rooms_address->longitude;

		if ($request->checkin != '' && $request->checkout != '') {
			$data['checkin'] = date('m/d/Y', strtotime($request->checkin));
			$data['checkout'] = date('m/d/Y', strtotime($request->checkout));
			$data['guests'] = $request->guests;
		} else {
			$data['checkin'] = '';
			$data['checkout'] = '';
			$data['guests'] = '';
		}
		if ($latitude != '' && $longitude != '') {
			$data['similar'] = Rooms::join('rooms_address', function ($join) {
				$join->on('rooms.id', '=', 'rooms_address.room_id');
			})
				->select(DB::raw('*,rooms.id as id, ( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
				->join('users', 'users.id', '=', 'rooms.user_id')
			/*->leftJoin('rooms_photos', function ($join) {
					$join->on('rooms.id', '=', 'rooms_photos.room_id')
						->where('featured', '=', 'Yes');
				})*/
				->having('distance', '<=', 30)
				->where('rooms.id', '!=', $request->id)
				->where('rooms.status', 'Listed')
				->where('users.status', 'Active')
				->get();
		} else {
			$data['similar'] = array();
		}
		/*echo "<pre>";
			print_r($data['similar']);die;
		*/
		$data['title'] = stripslashes( $data['result']->name ) . ' in ' . stripslashes( $data['result']->rooms_address->city );
		return view('rooms.rooms_detail', $data);
	}

	/**
	 * Load Rooms Detail Slider View
	 *
	 * @param array $request    Input values
	 * @return view rooms_slider
	 */
	public function rooms_slider(Request $request) {
		$data['room_id'] = $request->id;

		$data['result'] = Rooms::find($request->id);

		$data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();

		return view('rooms.rooms_slider', $data);
	}

	/**
	 * Ajax Update List Your Space Price
	 *
	 * @param array $request    Input values
	 * @return json success, currency_symbol, steps_count
	 */
	public function update_price(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		$price = RoomsPrice::find($request->id);

		$price->room_id = $request->id;

		foreach ($data as $key => $value) {
			$price->$key = $value;
			if ($key == 'weekend' && $value == '') {
				$price->thursday = 'No';
				$price->friday = 'No';
				$price->saturday = 'No';
			}
		}

		$price->save();

		$this->update_status($request->id);

		return json_encode(['success' => 'true', 'currency_symbol' => $price->currency->original_symbol, 'steps_count' => $price->steps_completed]);
	}

	/**
	 * Ajax List Your Space Steps Status
	 *
	 * @param array $request    Input values
	 * @return json rooms_steps_status result
	 */
	public function rooms_steps_status(Request $request) {
		return RoomsStepsStatus::find($request->id);
	}

	/**
	 * Ajax Rooms Related Table Data
	 *
	 * @param array $request    Input values
	 * @return json rooms, rooms_address, rooms_price, currency table results
	 */
	public function rooms_data(Request $request) {
		$data = Rooms::find($request->id);

		$rooms_address = array_merge($data->toArray(), $data->rooms_address->toArray());

		$rooms_price = array_merge($rooms_address, $data->rooms_price->toArray());

		$rooms_currency = array_merge($rooms_price, ['symbol' => $data->rooms_price->currency->symbol]);

		return json_encode($rooms_currency);
	}

	/**
	 * Ajax Rooms Detail Calendar Dates Blocking
	 *
	 * @param array $request    Input values
	 * @return json calendar results
	 */
	public function rooms_calendar(Request $request) {
		// For coupon code destroy
		Session::forget('coupon_code');
		Session::forget('coupon_amount');
		Session::forget('remove_coupon');
		Session::forget('manual_coupon');

		$id = $request->data;

		$result['not_avilable'] = Calendar::where('room_id', $id)->where('status', 'Not available')->get()->lists('date');

		$result['changed_price'] = Calendar::where('room_id', $id)->get()->lists('price', 'date');

		$result['price'] = RoomsPrice::where('room_id', $id)->get()->lists('night');

		$result['currency_symbol'] = Currency::find(1)->symbol;

		return json_encode($result);
	}

	public function rooms_calendar_alter(Request $request) {
		$id = $request->data;

		$checkin = date('Y-m-d', strtotime($request->checkin));
		$date2 = date('Y-m-d', strtotime($request->checkout));

		$checkout = date('Y-m-d', (strtotime('-1 day', strtotime($date2))));

		$booked_days = $this->get_days($checkin, $checkout);

		$result['not_avilable'] = Calendar::where('room_id', $id)->where('status', 'Not available')->whereNotIn('date', $booked_days)->get()->lists('date');

		$result['changed_price'] = Calendar::where('room_id', $id)->get()->lists('price', 'date');

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
	public function price_calculation(Request $request) {
		// For coupon code destroy
		Session::forget('coupon_code');
		Session::forget('coupon_amount');
		Session::forget('remove_coupon');
		Session::forget('manual_coupon');

		return $this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->guest_count, '', $request->change_reservation);
	}

	/**
	 * Get days between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate) {
		$aDays[] = $sStartDate;

		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}

	/**
	 * Ajax Update List Your Space Address
	 *
	 * @param array $request    Input values
	 * @return json success
	 */
	public function update_locations(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		$address = RoomsAddress::find($request->id);

		$address->room_id = $request->id;

		foreach ($data as $key => $value) {
			if ($key != 'country_full_name') {
				$address->$key = $value;
			}
		}

		$address->save();

		$this->update_status($request->id);

		$rooms = Rooms::find($request->id); // Where condition for Update

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Ajax Update List Your Space Description
	 *
	 * @param array $request    Input values
	 * @return json success
	 */
	public function update_description(Request $request) {
		$data = $request;

		$data = json_decode($data['data']); // AngularJS data decoding

		$price = RoomsDescription::find($request->id);

		$price->room_id = $request->id;

		foreach ($data as $key => $value) {
			$price->$key = str_replace("'", "\'", $value);
		}

		$price->save();
		$this->update_status($request->id);

		$rooms = Rooms::find($request->id); // Where condition for Update

		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Ajax Update List Your Space Calendar Dates Price, Status
	 *
	 * @param array $request    Input values
	 * @return empty
	 */
	public function calendar_edit(Request $request) {
		$start_date = \DateTime::createFromFormat('m-d-Y', $request->start_date);
		$start_date = $start_date->format('Y-m-d');
		$start_date = strtotime($start_date);

		$end_date = \DateTime::createFromFormat('m-d-Y', $request->end_date);
		$end_date = $end_date->format('Y-m-d');
		$end_date = strtotime($end_date);

		/*$start_date = date('Y-m-d', strtotime($request->start_date));
			$start_date = strtotime($start_date);

			$end_date = date('Y-m-d', strtotime($request->end_date));
			$end_date = strtotime($end_date);
		*/

		for ($i = $start_date; $i <= $end_date; $i += 86400) {
			$date = date("Y-m-d", $i);

			$data = ['room_id' => $request->id,
				'price' => ($request->price) ? $request->price : '0',
				'status' => "$request->status",
				'notes' => $request->notes,
			];

			Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $date], $data);
		}
		$rooms = Rooms::find($request->id);
		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	public function calendar_set_default(Request $request) {
		$first_date = \DateTime::createFromFormat('Y-m-d', $request->first_date);
		$first_date = $first_date->format('Y-m-d');
		$first_date = strtotime($first_date);

		$last_date = \DateTime::createFromFormat('Y-m-d', $request->last_date);
		$last_date = $last_date->format('Y-m-d');
		$last_date = strtotime($last_date);

		for ($i = $first_date; $i <= $last_date; $i += 86400) {
			$date = date("Y-m-d", $i);

			$data = [
				'room_id' => $request->id,
				'status' => ucfirst($request->status)
			];

			if($request->status == 'Available') {
				Calendar::where(['room_id' => $request->id, 'date' => $date, 'status' => 'Not available'])->delete();
			} else {
				Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $date], $data);
			}

		}
		$rooms = Rooms::find($request->id);
		return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_completed]);
	}

	/**
	 * Contact Request send to Host
	 *
	 * @param array $request Input values
	 * @return redirect to Rooms Detail page
	 */
	public function contact_request(Request $request, EmailController $email_controller) {
		$data['price_list'] = json_decode($this->payment_helper->price_calculation($request->id, $request->message_checkin, $request->message_checkout, $request->message_guests));

		if (@$data['price_list']->status == 'Not available') {
			$this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
			return redirect('rooms/' . $request->id);
		}

		$rooms = Rooms::find($request->id);

		$reservation = new Reservation;

		$reservation->room_id = $request->id;
		$reservation->host_id = $rooms->user_id;
		$reservation->user_id = Auth::user()->user()->id;
		$reservation->checkin = $this->payment_helper->date_convert($request->message_checkin);
		$reservation->checkout = $this->payment_helper->date_convert($request->message_checkout);
		$reservation->number_of_guests = $request->message_guests;
		$reservation->nights = $data['price_list']->total_nights;
		$reservation->per_night = $data['price_list']->rooms_price;
		$reservation->subtotal = $data['price_list']->subtotal;
		$reservation->cleaning = $data['price_list']->cleaning_fee;
		$reservation->additional_guest = $data['price_list']->additional_guest;
		$reservation->security = $data['price_list']->security_fee;
		$reservation->service = $data['price_list']->service_fee;
		$reservation->host_fee = $data['price_list']->host_fee;
		$reservation->total = $data['price_list']->total;
		$reservation->currency_code = $data['price_list']->currency;
		$reservation->type = 'contact';
		$reservation->country = 'US';

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

		$message->room_id = $request->id;
		$message->reservation_id = $reservation->id;
		$message->user_to = Rooms::where(['id' => $request->id])->first()->user_id;
		$message->user_from = Auth::user()->user()->id;
		$message->message = $question;
		$message->message_type = 9;
		$message->read = 0;

		$message->save();

		$email_controller->inquiry($reservation->id, $question);

		$this->helper->flash_message('success', trans('messages.rooms.contact_request_has_sent', ['first_name' => $rooms->users->first_name])); // Call flash message function
		return redirect('rooms/' . $request->id);
	}
}
