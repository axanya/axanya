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

namespace App\Http\Controllers\Admin;

use App\DataTables\RoomsDataTable;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\BedType;
use App\Models\Calendar;
use App\Models\Country;
use App\Models\Currency;
use App\Models\GuestGender;
use App\Models\PropertyType;
use App\Models\ReligiousAmenities;
use App\Models\ReligiousAmenitiesType;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\RoomsDescription;
use App\Models\RoomsPhotos;
use App\Models\RoomsPolicies;
use App\Models\RoomsPrice;
use App\Models\RoomsStepsStatus;
use App\Models\RoomsBathroom;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Http\Request;

class RoomsController extends Controller {
	protected $helper; // Global variable for instance of Helpers

	public function __construct() {
		$this->helper = new Helpers;
	}

	/**
	 * Load Datatable for Rooms
	 *
	 * @param array $dataTable  Instance of RoomsDataTable
	 * @return datatable
	 */
	public function index(RoomsDataTable $dataTable) {
		return $dataTable->render('admin.rooms.view');
	}

	/**
	 * Add a New Room
	 *
	 * @param array $request  Input values
	 * @return redirect     to Rooms view
	 */
	public function add(Request $request) {
		$rooms = Rooms::whereNull('user_id')->whereNull('status')->first();

		if (!$rooms) {
			$rooms = new Rooms;

			$rooms->property_type = 1;
			$rooms->room_type = 1;
			$rooms->accommodates = 1;
			$rooms->guest_gender = 3;
			$rooms->started = 'Yes';

			$rooms->save(); // Store data to rooms Table

			$rooms_address = new RoomsAddress;

			$rooms_address->room_id = $rooms->id;

			$rooms_address->save(); // Store data to rooms_address Table

			$rooms_price = new RoomsPrice;

			$rooms_price->room_id = $rooms->id;
			$rooms_price->currency_code = 'USD';

			$rooms_price->save(); // Store data to rooms_price table

			$rooms_status = new RoomsStepsStatus;

			$rooms_status->room_id = $rooms->id;

			$rooms_status->save(); // Store data to rooms_steps_status table

			$rooms_description = new RoomsDescription;

			$rooms_description->room_id = $rooms->id;

			$rooms_description->save(); // Store data to rooms_description table
		}

		return redirect('admin/edit_room/' . $rooms->id);

		/*if (!$_POST) {
				$bedrooms = [];
				$bedrooms[0] = 'Studio';
				for ($i = 1; $i <= 10; $i++) {
					$bedrooms[$i] = $i;
				}

				$beds = [];
				for ($i = 1; $i <= 16; $i++) {
					$beds[$i] = ($i == 16) ? $i . '+' : $i;
				}

				$bathrooms = [];
				$bathrooms[0] = 0;
				for ($i = 0.5; $i <= 8; $i += 0.5) {
					$bathrooms["$i"] = ($i == 8) ? $i . '+' : $i;
				}

				$accommodates = [];
				for ($i = 1; $i <= 16; $i++) {
					$accommodates[$i] = ($i == 16) ? $i . '+' : $i;
				}

				$data['bedrooms'] = $bedrooms;
				$data['beds'] = $beds;
				$data['bed_type'] = BedType::lists('name', 'id');
				$data['bathrooms'] = $bathrooms;
				$data['property_type'] = PropertyType::lists('name', 'id');
				$data['room_type'] = RoomType::lists('name', 'id');
				$data['accommodates'] = $accommodates;
				$data['country'] = Country::lists('long_name', 'short_name');
				$data['amenities'] = Amenities::active_all();
				$data['users_list'] = User::lists('first_name', 'id');

				return view('admin.rooms.add', $data);
			} else if ($_POST) {
				$rooms = new Rooms;

				$rooms->user_id = $request->user_id;
				$rooms->calendar_type = $request->calendar;
				$rooms->bedrooms = $request->bedrooms;
				$rooms->beds = $request->beds;
				$rooms->bed_type = $request->bed_type;
				$rooms->bathrooms = $request->bathrooms;
				$rooms->property_type = $request->property_type;
				$rooms->room_type = $request->room_type;
				$rooms->accommodates = $request->accommodates;
				$rooms->name = $request->name;
				$rooms->sub_name = RoomType::find($request->room_type)->name . ' in ' . $request->city;
				$rooms->summary = $request->summary;
				$rooms->amenities = @implode(',', @$request->amenities);
				$rooms->booking_type = $request->booking_type;
				$rooms->started = 'Yes';
				$rooms->status = 'Listed';
				$rooms->cancel_policy = $request->cancel_policy;

				$rooms->save();

				$rooms_address = new RoomsAddress;

				$rooms_address->room_id = $rooms->id;
				$rooms_address->address_line_1 = $request->address_line_1;
				$rooms_address->address_line_2 = $request->address_line_2;
				$rooms_address->city = $request->city;
				$rooms_address->state = $request->state;
				$rooms_address->country = $request->country;
				$rooms_address->postal_code = $request->postal_code;
				$rooms_address->latitude = $request->latitude;
				$rooms_address->longitude = $request->longitude;

				$rooms_address->save();

				$rooms_description = new RoomsDescription;

				$rooms_description->room_id = $rooms->id;
				$rooms_description->space = $request->space;
				$rooms_description->access = $request->access;
				$rooms_description->interaction = $request->interaction;
				$rooms_description->notes = $request->notes;
				$rooms_description->house_rules = $request->house_rules;
				$rooms_description->neighborhood_overview = $request->neighborhood_overview;
				$rooms_description->transit = $request->transit;

				$rooms_description->save();

				$rooms_price = new RoomsPrice;

				$rooms_price->room_id = $rooms->id;
				$rooms_price->night = $request->night;
				$rooms_price->week = $request->week;
				$rooms_price->month = $request->month;
				$rooms_price->cleaning = $request->cleaning;
				$rooms_price->additional_guest = $request->additional_guest;
				$rooms_price->guests = ($request->additional_guest) ? $request->guests : '0';
				$rooms_price->security = $request->security;
				$rooms_price->weekend = $request->weekend;
				$rooms_price->currency_code = $request->currency_code;

				$rooms_price->save();

				// Image upload
				if (isset($_FILES["photos"]["name"])) {
					foreach ($_FILES["photos"]["error"] as $key => $error) {
						$tmp_name = $_FILES["photos"]["tmp_name"][$key];

						$name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);

						$ext = pathinfo($name, PATHINFO_EXTENSION);

						$name = time() . '_' . $name;

						$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $rooms->id;

						if (!file_exists($filename)) {
							mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $rooms->id, 0777, true);
						}

						if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
							if (move_uploaded_file($tmp_name, "images/rooms/" . $rooms->id . "/" . $name)) {
								$photos = new RoomsPhotos;
								$photos->room_id = $rooms->id;
								$photos->name = $name;
								$photos->save();
							}
						}
					}

					$photos_featured = RoomsPhotos::where('room_id', $rooms->id)->where('featured', 'Yes');

					if ($photos_featured->count() == 0) {
						$photos = RoomsPhotos::where('room_id', $rooms->id)->first();
						$photos->featured = 'Yes';
						$photos->save();
					}
				}

				$rooms_steps = new RoomsStepsStatus;

				$rooms_steps->room_id = $rooms->id;
				$rooms_steps->basics = 1;
				$rooms_steps->description = 1;
				$rooms_steps->location = 1;
				$rooms_steps->photos = 1;
				$rooms_steps->pricing = 1;
				$rooms_steps->calendar = 1;

				$rooms_steps->save();

				$this->helper->flash_message('success', 'Room Added Successfully'); // Call flash message function
				return redirect('admin/rooms');
			} else {
				return redirect('admin/rooms');
		*/
	}

	/**
	 * Update Room Details
	 *
	 * @param array $request    Input values
	 * @return redirect     to Rooms View
	 */
	public function update(Request $request, CalendarController $calendar) {
		if (!$_POST) {
			$bedrooms = [];
			$bedrooms[0] = 'Studio';
			for ($i = 1; $i <= 10; $i++) {
				$bedrooms[$i] = $i;
			}

			$beds = [];
			for ($i = 1; $i <= 16; $i++) {
				$beds[$i] = ($i == 16) ? $i . '+' : $i;
			}

			$bathrooms = [];
			$bathrooms[0] = 0;
			for ($i = 0.5; $i <= 8; $i += 0.5) {
				$bathrooms["$i"] = ($i == 8) ? $i . '+' : $i;
			}

			$accommodates = [];
			for ($i = 1; $i <= 16; $i++) {
				$accommodates[$i] = ($i == 16) ? $i . '+' : $i;
			}

			$data['bedrooms'] = $bedrooms;
			$data['beds'] = $beds;
			$data['bed_type'] = BedType::lists('name', 'id');
			$data['bathrooms'] = $bathrooms;
			$data['property_type'] = PropertyType::lists('name', 'id');
			$data['room_type'] = RoomType::lists('name', 'id');
			$data['guest_gender'] = GuestGender::dropdown();
			$data['accommodates'] = $accommodates;
			$data['country'] = Country::lists('long_name', 'short_name');
			//$data['amenities'] = Amenities::active_all();

			$data['amenities'] = Amenities::active_all();
			$data['amenities_type'] = AmenitiesType::active_all();

			$data['religious_amenities'] = ReligiousAmenities::active_all();
			$data['religious_amenities_types'] = ReligiousAmenitiesType::active_all();

			$data['rooms_policies'] = RoomsPolicies::where('room_id', $request->id)->first();

			$data['users_list'] = User::lists('first_name', 'id');
			$data['room_id'] = $request->id;
			$data['result'] = Rooms::find($request->id);
			$data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();
			$data['calendar'] = str_replace(['<form name="calendar-edit-form">', '</form>', url('manage-listing/' . $request->id . '/calendar')], ['', '', 'javascript:void(0);'], $calendar->generate($request->id));
			$data['prev_amenities'] = explode(',', $data['result']->amenities);

			$data['prev_religious_amenities'] = explode(',', $data['result']->religious_amenities);
			$data['get_currency'] = Currency::get();
			$data['rooms_status'] = RoomsStepsStatus::where('room_id', $request->id)->first();

			$data['users_list'] = User::lists('first_name', 'id');

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

			return view('admin.rooms.edit', $data);
		} else if ($request->submit == 'basics') {
			$rooms = Rooms::find($request->room_id);

			$rooms->bedrooms = $request->bedrooms;
			$rooms->beds = $request->beds;
			$rooms->bed_type = $request->bed_type;
			$rooms->bathrooms = $request->bathrooms;
			$rooms->property_type = $request->property_type;
			$rooms->room_type = $request->room_type;
			$rooms->accommodates = $request->accommodates;
			$rooms->guest_gender = $request->guest_gender;

			$rooms->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'booking_type') {
			$rooms = Rooms::find($request->room_id);

			$rooms->booking_type = $request->booking_type;

			$rooms->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'description') {
			$rooms = Rooms::find($request->room_id);

			$rooms->name = $request->name;
			$rooms->sub_name = RoomType::find($request->room_type)->name . ' in ' . $request->city;
			$rooms->summary = $request->summary;

			$rooms->save();

			$rooms_description = RoomsDescription::find($request->room_id);

			$rooms_description->space = $request->space;
			$rooms_description->access = $request->access;
			$rooms_description->interaction = $request->interaction;
			$rooms_description->notes = $request->notes;
			$rooms_description->house_rules = $request->house_rules;
			$rooms_description->neighborhood_overview = $request->neighborhood_overview;
			$rooms_description->transit = $request->transit;

			$rooms_description->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'location') {
			$rooms_address = RoomsAddress::find($request->room_id);

			$rooms_address->address_line_1 = $request->address_line_1;
			$rooms_address->address_line_2 = $request->address_line_2;
			$rooms_address->city = $request->city;
			$rooms_address->state = $request->state;
			$rooms_address->country = $request->country;
			$rooms_address->postal_code = $request->postal_code;
			$rooms_address->latitude = $request->latitude;
			$rooms_address->longitude = $request->longitude;

			$rooms_address->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'amenities') {
			$rooms = Rooms::find($request->room_id);

			$rooms->amenities = @implode(',', @$request->amenities);

			$rooms->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'photos') {
			// Image upload
			if (isset($_FILES["photos"]["name"])) {
				foreach ($_FILES["photos"]["error"] as $key => $error) {
					$tmp_name = $_FILES["photos"]["tmp_name"][$key];

					$name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);

					$ext = pathinfo($name, PATHINFO_EXTENSION);

					$name = time() . '_' . $name;

					$filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $request->room_id;

					if (!file_exists($filename)) {
						mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $request->room_id, 0777, true);
					}

					if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') {
						if (move_uploaded_file($tmp_name, "images/rooms/" . $request->room_id . "/" . $name)) {
							$photos = new RoomsPhotos;
							$photos->room_id = $request->room_id;
							$photos->name = $name;
							$photos->save();
						}
					}
				}

				$photos_featured = RoomsPhotos::where('room_id', $request->room_id)->where('featured', 'Yes');

				if ($photos_featured->count() == 0) {
					$photos = RoomsPhotos::where('room_id', $request->room_id)->first();
					$photos->featured = 'Yes';
					$photos->save();
				}
			}
			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'pricing') {
			$rooms_price = RoomsPrice::find($request->room_id);

			$rooms_price->night = $request->night;
			$rooms_price->week = $request->week;
			$rooms_price->month = $request->month;
			$rooms_price->cleaning = $request->cleaning;
			$rooms_price->additional_guest = $request->additional_guest;
			$rooms_price->guests = ($request->additional_guest) ? $request->guests : '0';
			$rooms_price->security = $request->security;
			$rooms_price->weekend = $request->weekend;
			$rooms_price->currency_code = $request->currency_code;

			$rooms_price->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'terms') {
			$rooms = Rooms::find($request->room_id);

			$rooms->cancel_policy = $request->cancel_policy;

			$rooms->save();

			$this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
			return redirect('admin/rooms');
		} else if ($request->submit == 'cancel') {
			return redirect('admin/rooms');
		} else {
			return redirect('admin/rooms');
		}
	}

	/**
	 * Delete Rooms
	 *
	 * @param array $request    Input values
	 * @return redirect     to Rooms View
	 */
	public function delete(Request $request) {
		$id = $request->id;
		RoomsPolicies::where(['room_id' => $id])->delete();
		RoomsPhotos::where(['room_id' => $id])->delete();
		RoomsPrice::where(['room_id' => $id])->delete();
		RoomsDescription::where(['room_id' => $id])->delete();
		RoomsBathroom::where(['room_id' => $id])->delete();
		RoomsAddress::where(['room_id' => $id])->delete();
		RoomsStepsStatus::where(['room_id' => $id])->delete();
		Calendar::where(['room_id' => $id])->delete();
		Rooms::find($id)->delete();

		$this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
		return redirect('admin/rooms');
	}

	/**
	 * Users List for assign Rooms Owner
	 *
	 * @param array $request    Input values
	 * @return json Users table
	 */
	public function users_list(Request $request) {
		return User::where('first_name', 'like', $request->term . '%')->select('first_name as value', 'id')->get();
	}

	/**
	 * Ajax function of Calendar Dropdown and Arrow
	 *
	 * @param array $request    Input values
	 * @param array $calendar   Instance of CalendarController
	 * @return html Calendar
	 */
	public function ajax_calendar(Request $request, CalendarController $calendar) {
		$data_calendar = @json_decode($request['data']);
		$year = @$data_calendar->year;
		$month = @$data_calendar->month;
		$data['calendar'] = str_replace(['<form name="calendar-edit-form">', '</form>', url('manage-listing/' . $request->id . '/calendar')], ['', '', 'javascript:void(0);'], $calendar->generate($request->id, $year, $month));

		return $data['calendar'];
	}

	/**
	 * Delete Rooms Photo
	 *
	 * @param array $request    Input values
	 * @return json success
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

		return json_encode(['success' => 'true']);
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

	public function popular(Request $request) {
		$prev = Rooms::find($request->id)->popular;

		if ($prev == 'Yes') {
			Rooms::where('id', $request->id)->update(['popular' => 'No']);
		} else {
			Rooms::where('id', $request->id)->update(['popular' => 'Yes']);
		}

		$this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
		return redirect('admin/rooms');
	}
}
