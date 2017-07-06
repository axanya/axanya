<?php

/**
 * Calendar Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Calendar
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IcalController;
use App\Models\Calendar;
use App\Models\ImportedIcal;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\RoomsPrice;
use App\Models\RoomsStepsStatus;
use Auth;
use Form;
use Illuminate\Http\Request;
use Validator;

class CalendarController extends Controller {
	public $start_day = 'sunday'; // Global Variable for Start Day of Calendar

	public function __construct() {
		if (Auth::user()->user()) {
			date_default_timezone_set(Auth::user()->user()->timezone);
		}
	}

	/**
	 * Get a Calendar HTML
	 *
	 * @param int $room_id  Room Id for get the Calendar data
	 * @param int $year     Year of Calendar
	 * @param int $month    Month of Calendar
	 * @return html
	 */
	public function get_calendar(Request $request) {
		$room_id = $request->id;
		$rooms = Rooms::find($room_id);
		$rooms->calendar_length = 18;
		$rooms->save(); // Save rooms data to rooms table

		$rooms_status = RoomsStepsStatus::find($room_id);
		$rooms_status->calendar = 1;
		$rooms_status->save();

		$year = $month = '';
		// Get Room Price for Give Room ID
		$rooms_price = RoomsPrice::find($request->id);

		// Set and validate the supplied month/year
		if ($year == '') {
			$year = date('Y');
		}

		if ($month == '') {
			$month = date('m');
		}

		// Determine the total days in the month
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		// Set the starting day of the week
		$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);

		$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day = $start_day + 1 - $date["wday"];
		$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
		$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

		// Set the previous & next month
		$prev_month = date('m', $prev_time);
		$next_month = date('m', $next_time);

		// Set the previous & next year
		$prev_year = date('Y', $prev_time);
		$next_year = date('Y', $next_time);

		// Set the current day, month & year
		$cur_day = date('j');
		$cur_year = date('Y');
		$cur_month = date('m');

		// Determine the total days in the previous month
		$prev_total_days = date('t', $prev_time);

		while ($day > 1) {
			$day -= 7;
		}

		// Begin building the calendar output
		$out = '<div class="col-lg-8 col-sm-12 col-xs-12 rtl-right"><div class="host-calendar-container">
					<div class="calendar-month">';

		$out .= //'<div class="row-space-2 deselect-on-click"> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-previous panel text-center" data-year="'.$prev_year.'" data-month="'.$prev_month.'"> <i class="icon icon-chevron-left h3"></i> </a> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-next panel text-center" data-year="'.$next_year.'" data-month="'.$next_month.'"> <i class="icon icon-chevron-right h3"></i> </a> <div class="current-month-selection"> <h2> <span>'.date('F Y', $local_date).'</span> <span> &nbsp;</span> <span class="current-month-arrow">▾</span> </h2>'.Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'calendar_dropdown', 'data-href' => url('manage-listing/'.$room_id.'/calendar')]).'<div class="spinner-next-to-month-nav">Just a moment...</div></div> </div>'
		'<div class="days-of-week deselect-on-click">
			<ul class="list-layout clearfix"> <li>Sun</li> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li>
			</ul>
		</div>';

		$out .= '';
		$out .= '<div id="calendar_selection">';
		$out .= '<div class="days-container panel clearfix"> <ul class="list-unstyled">';

		$temp_total_days = $total_days;
		$temp_next_month = $next_month;
		$temp_next_year = $next_year;
		$effectiveDate = $year . "-" . $cur_month . "-01";
		// Build the main body of the calendar
		for ($j = 0; $j < $request->months; $j++) {
			$add_month = "+" . $j . " months";
			//$effectiveDate = date('Y-m-d', strtotime());
			if ($j != 0) {
				$effectiveDate = date('Y-m-d', strtotime("+1 month", strtotime($effectiveDate)));
				$year = date('Y', strtotime($effectiveDate));
			}

			$month = date('m') + $j;
			// echo $running_month."----".$year;
			if ($month > 12) {
				$month = $month - 12;
			}
			$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			// Determine the total days in the month
			//$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			// Set the starting day of the week
			$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
			$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

			// Set the starting day number
			$local_date = mktime(12, 0, 0, $month, 1, $year);
			$date = getdate($local_date);
			$day = $start_day + 1 - $date["wday"];

			$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
			$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

			// Set the previous & next month
			$prev_month = date('m', $prev_time);
			$next_month = date('m', $next_time);

			// Set the previous & next year
			$prev_year = date('Y', $prev_time);
			$next_year = date('Y', $next_time);

			// Set the current day, month & year
			$cur_day = date('j');
			$cur_year = date('Y');
			$cur_month = date('m');

			// Determine the total days in the previous month
			$prev_total_days = date('t', $prev_time);

			while ($day > 1) {
				$day -= 7;
			}
			if ($j > 0 && $day != 1) {
				$day = $day + 7;
			}
			//$day = 1;
			$year_changed = false;
			while ($day <= $total_days) {

				for ($i = 0; $i < 7; $i++) {
					$check_valid_date = false;
					$price_class = $available_class = $bottom_green = '';
					// Set HTML Class for Previous, Current and Next month tiles
					if ($day < $cur_day && $year <= $cur_year && $month <= $cur_month) {
						$class = 'tile-previous';
					} else if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
						$class = 'tile-previous';
					} else if ($year <= $cur_year && $month < $cur_month) {
						$class = 'tile-previous new-year';
					} else {
						$class = '';
						$bottom_green = 'bottom-green';
						$price_class = 'price-green';
						$check_valid_date = true;
						$available_class = 'available-date';
					}

					/*if($year > $cur_year)
                        $class = '';*/

					// Mention Current Date as Today
					if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
						$today = '<span class="today-label"></span>'; //Today
					} else {
						$today = '';
					}

					if ($day > 0 && $day <= $total_days) {
						$date = $year . '-' . $month . '-' . $day;

						$final_day = $day;
					} else {

						if ($day <= 0) {
							$day_prev = $prev_total_days + $day;

							$date = $prev_year . '-' . $prev_month . '-' . $day_prev;

							$final_day = $day_prev;
						} else if ($day > $total_days) {
							$day_next = $day - $total_days;

							$date = $next_year . '-' . $next_month . '-' . $day_next;

							$final_day = $day_next;

							if ($day > $temp_total_days) {
								$day_next = $day - $temp_total_days;
								$date = $temp_next_year . '-' . $temp_next_month . '-' . $day_next;
								$final_day = $day_next;

								$next_time = mktime(12, 0, 0, $next_month, 1, $next_year);
								$next_month = date('m', $next_time);
								$next_year = date('Y', $next_time);

								$day_next = $day - $temp_total_days;
								$temp_total_days += cal_days_in_month(CAL_GREGORIAN, $next_month, $next_year);
								//$day_next  = $day - $temp_total_days;
								$date = $next_year . '-' . $next_month . '-' . $day_next;
								$final_day = $day_next;
							}
						}
					}

					if ($class == '' || $class == 'today price-suggestion-undefined' || $class == 'tile-previous') {
						if ($rooms_price->status($date) == 'Not available' && $check_valid_date == true) {
							$class = 'status-b';
							$available_class = 'available-date';
							$bottom_green = '';
						}
					}

					$current_year = date('Y', strtotime($date));
					$next_year_class = '';
					if ($year != $current_year && !$year_changed) {
						$year_changed = true;
						$next_year_class = 'next-year';

						if ($i < 6) {
							//$total_blank_i = 6 - $i;
							for ($blank_i = 0; $blank_i < (7 - $i); $blank_i++) {
								$out .= '<li class="tile  no-tile-status both"> <div class="date">&nbsp;</div></li>';
							}

							$out .= '<li class="new-year"><h4>' . $current_year . '</h4></li>';

							for ($blank_i2 = 0; $blank_i2 < (7 - $blank_i); $blank_i2++) {
								$out .= '<li class="tile  no-tile-status both"> <div class="date">&nbsp;</div></li>';
							}

						} else {
							$out .= '<li class="tile  no-tile-status both"> <div class="date">&nbsp;</div></li>';
						}

					}
					$day_price = '';
					if ($check_valid_date) {
						$day_price = $rooms_price->price($date);
						$currency_symbol = $rooms_price->currency->symbol;
					}

					$out .= '<li class="tile ' . $class . ' ' . $available_class . ' ' . $bottom_green . ' ' . $next_year_class . ' no-tile-status both" id="' . $date . '" data-day="' . $day . '" data-month="' . $month . '" data-year="' . $year . '" data-price = "' . $day_price . '"> <div class="date"><span class ="day-number"> <span>';
					$month_name = date('F', strtotime($date));

					if ($final_day == 1) {
						$month_str = '<div class="month-name">' . $month_name . '</div>';
					} else {
						$month_str = '';
					}
					$out .= $final_day . '</span> ' . $today . ' </span> ' . $month_str . '</div>';

					if ($class != 'status-b') {
						$currency_symbol = '';
						if ($check_valid_date) {
							$currency_symbol = $rooms_price->currency->symbol;
						}

						$out .= '<div class="price ' . $price_class . '" style="display: inline-flex;"> <span>' . $currency_symbol . '</span> <span>' . $day_price . '</span> </div>';
					}

					if ($rooms_price->notes($date) != '') {
						$out .= '<div class="tile-notes"><div class="va-container va-container-v va-container-h"><span class="va-middle tile-notes-text">' . $rooms_price->notes($date) . '</span></div></div>';
					}

					$day++;
				}
			}

		}

		$out .= '</ul> </div>';

		// HTML of Change calendar tiles price and status
		$out .= '</div></div>
		<div class="modal_done">
			<button type="button" data-behavior="modal-close" style="display:none">Done</button>
		</div>

        </div></div>
        <div class="col-lg-4 col-sm-12 col-xs-12 ng-scope mt60"><i class="fa fa-caret-left" aria-hidden="true"></i><span class="side-text"> ' . trans('messages.lys.pointer_note') . '<p></p></span></div>
        <div class="col-lg-4 col-sm-12 col-xs-12" style="margin-top:40px">
        <div class="modal-content content-container" id="custom_calendar" style="display:none;top:20%">
    			<div class="panel">
    				<a data-behavior="modal-close" class="cal-close" href=""></a>
    				<div class="calendar-edit-form panel host-calendar-sidebar-item">
    					<form name="calendar-edit-form">
    						<div class="panel-header text-center panel-header-small">
    							<div class="segmented-control">
    								<h7>&nbsp;</h7>
    							</div>
    						</div>


    						<div class="panel-body">
    							<div class="row text-muted text-center">
    								<div class="col-6">
    									<label>Start Date</label>
    								</div>
    								<div class="col-6">
    									<label>End Date</label>
    								</div>
    							</div>
    							<div class="row">
    								<div class="col-6">
    									<input type="text" class="ui-datepicker-target" id="calendar-edit-start" ng-model="calendar_edit_start_date">
    								</div>
    								<div class="col-6">
    									<input type="text" class="ui-datepicker-target" id="calendar-edit-end" ng-model="calendar_edit_end_date">
    								</div>
    							</div>
    						</div>

    						<div class="row" ng-init="available_status = &quot;available&quot;">
								<div class="col-md-6 col-sm-6 col-xs-6 text-right rtl-right rtl-text-left">
									<div class="radio">
									    <label>
									        <input name="available_status" ng-model="available_status" id="available_status" value="available" type="radio">
									        Available
									    </label>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6 rtl-right">
									<div class="radio">
									    <label>
									        <input name="available_status" ng-model="available_status" id="blocked_status" value="not available" type="radio">
									        Blocked
									    </label>
									</div>
								</div>
							</div>

    						<div class="panel-body">
    							<div class="sidebar-price-container row-space-1 " ng-show="available_status == \'available\'">
    								<label>Price for each night</label>
    								<div class="embedded-currency">
    									<input type="number" style="padding-left: 32px;" value="" class="input-giant sidebar-price embedded-currency__input" ng-model="calendar_edit_price">
    									<span style="top: 13px; left: 36px;" class="embedded-currency__currency embedded-currency__currency--in-input">' . $rooms_price->currency->symbol . '</span>
    									<div style="font-size: 25px; line-height: normal; font-weight: bold; padding: 8px 20px; border: 1px solid rgb(196, 196, 196); width: 160px;" class="input-giant sidebar-price embedded-currency__input embedded-currency__input--invisible">
    										<span class="embedded-currency__currency">' . $rooms_price->currency->symbol . '</span>
    										<span class="clone-value"></span>
    									</div>
    								</div>
    								<p id="price-error" style="display:none;" class="red">Please enter valid price</p>
    							</div>
    							<div class="row-space-2 onboarding-dim">
    								<div>
    									<a data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" class="text-muted link-icon" onclick="return false;" ng-click="isAddNote = !isAddNote">
    										<span class="link-icon__text">Add note</span>
    										<i class="icon icon-caret-down"></i>
    									</a>
    									<textarea ng-model="notes" ng-show="isAddNote"></textarea>
    								</div>
    							</div>
    						</div>


    						<div class="panel-footer panel-footer-flex onboarding-dim ">
    							<a class="btn" data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" id="calendar_edit_cancel">Cancel</a>
    							<button type="submit" class="btn btn-host" ng-click="calendar_edit_submit(&quot;' . url('manage-listing/' . $room_id . '/calendar') . '&quot;)">Save changes</button>
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
        </div>';

		return $out;
	}

	/**
	 * Get a Calendar HTML
	 *
	 * @param int $room_id  Room Id for get the Calendar data
	 * @param int $year     Year of Calendar
	 * @param int $month    Month of Calendar
	 * @return html
	 */
	public function generate($room_id = '', $year = '', $month = '') {
		//$month = 18;
		// Get Room Price for Give Room ID
		$rooms_price = RoomsPrice::find($room_id);

		// Set and validate the supplied month/year
		if ($year == '') {
			$year = date('Y');
		}

		if ($month == '') {
			$month = date('m');
		}

		// Determine the total days in the month
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		// Set the starting day of the week
		$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);

		$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day = $start_day + 1 - $date["wday"];
		$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
		$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

		// Set the previous & next month
		$prev_month = date('m', $prev_time);
		$next_month = date('m', $next_time);

		// Set the previous & next year
		$prev_year = date('Y', $prev_time);
		$next_year = date('Y', $next_time);

		// Set the current day, month & year
		$cur_day = date('j');
		$cur_year = date('Y');
		$cur_month = date('m');

		// Determine the total days in the previous month
		$prev_total_days = date('t', $prev_time);

		while ($day > 1) {
			$day -= 7;
		}

		// Begin building the calendar output
		$out = '<div class="col-lg-8 col-sm-12 col-xs-12"><div class="host-calendar-container">
					<div class="calendar-month">';

		$out .= //'<div class="row-space-2 deselect-on-click"> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-previous panel text-center" data-year="'.$prev_year.'" data-month="'.$prev_month.'"> <i class="icon icon-chevron-left h3"></i> </a> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-next panel text-center" data-year="'.$next_year.'" data-month="'.$next_month.'"> <i class="icon icon-chevron-right h3"></i> </a> <div class="current-month-selection"> <h2> <span>'.date('F Y', $local_date).'</span> <span> &nbsp;</span> <span class="current-month-arrow">▾</span> </h2>'.Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'calendar_dropdown', 'data-href' => url('manage-listing/'.$room_id.'/calendar')]).'<div class="spinner-next-to-month-nav">Just a moment...</div></div> </div>'
		'<div class="days-of-week deselect-on-click">
			<ul class="list-layout clearfix"> <li>Sun</li> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li>
			</ul>
		</div>';

		$out .= '';
		$out .= '<div id="calendar_selection">';
		$out .= '<div class="days-container panel clearfix"> <ul class="list-unstyled">';

		$temp_total_days = $total_days;
		$temp_next_month = $next_month;
		$temp_next_year = $next_year;
		$effectiveDate = $year . "-" . $cur_month . "-01";
		// Build the main body of the calendar
		for ($j = 0; $j < $month; $j++) {
			$add_month = "+" . $j . " months";
			//$effectiveDate = date('Y-m-d', strtotime());
			if ($j != 0) {
				$effectiveDate = date('Y-m-d', strtotime("+1 month", strtotime($effectiveDate)));
				$year = date('Y', strtotime($effectiveDate));
			}

			$month = date('m') + $j;
			// echo $running_month."----".$year;
			if ($month > 12) {
				$month = $month - 12;
			}
			$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			// Determine the total days in the month
			//$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			// Set the starting day of the week
			$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
			$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

			// Set the starting day number
			$local_date = mktime(12, 0, 0, $month, 1, $year);
			$date = getdate($local_date);
			$day = $start_day + 1 - $date["wday"];

			$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
			$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

			// Set the previous & next month
			$prev_month = date('m', $prev_time);
			$next_month = date('m', $next_time);

			// Set the previous & next year
			$prev_year = date('Y', $prev_time);
			$next_year = date('Y', $next_time);

			// Set the current day, month & year
			$cur_day = date('j');
			$cur_year = date('Y');
			$cur_month = date('m');

			// Determine the total days in the previous month
			$prev_total_days = date('t', $prev_time);

			while ($day > 1) {
				$day -= 7;
			}
			if ($j > 0 && $day != 1) {
				$day = $day + 7;
			}
			//$day = 1;
			$year_changed = false;
			while ($day <= $total_days) {

				for ($i = 0; $i < 7; $i++) {
					$check_valid_date = false;
					$price_class = $available_class = $bottom_green = '';
					// Set HTML Class for Previous, Current and Next month tiles
					if ($day < $cur_day && $year <= $cur_year && $month <= $cur_month) {
						$class = 'tile-previous';
					} else if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
						$class = 'tile-previous';
					} else if ($year <= $cur_year && $month < $cur_month) {
						$class = 'tile-previous new-year';
					} else {
						$class = '';
						$bottom_green = 'bottom-green';
						$price_class = 'price-green';
						$check_valid_date = true;
						$available_class = 'available-date';
					}

					/*if($year > $cur_year)
                        $class = '';*/

					// Mention Current Date as Today
					if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
						$today = '<span class="today-label"></span>'; //Today
					} else {
						$today = '';
					}

					if ($day > 0 && $day <= $total_days) {
						$date = $year . '-' . $month . '-' . $day;

						$final_day = $day;
					} else {

						if ($day <= 0) {
							$day_prev = $prev_total_days + $day;

							$date = $prev_year . '-' . $prev_month . '-' . $day_prev;

							$final_day = $day_prev;
						} else if ($day > $total_days) {
							$day_next = $day - $total_days;

							$date = $next_year . '-' . $next_month . '-' . $day_next;

							$final_day = $day_next;

							if ($day > $temp_total_days) {
								$day_next = $day - $temp_total_days;
								$date = $temp_next_year . '-' . $temp_next_month . '-' . $day_next;
								$final_day = $day_next;

								$next_time = mktime(12, 0, 0, $next_month, 1, $next_year);
								$next_month = date('m', $next_time);
								$next_year = date('Y', $next_time);

								$day_next = $day - $temp_total_days;
								$temp_total_days += cal_days_in_month(CAL_GREGORIAN, $next_month, $next_year);
								//$day_next  = $day - $temp_total_days;
								$date = $next_year . '-' . $next_month . '-' . $day_next;
								$final_day = $day_next;
							}
						}
					}

					if ($class == '' || $class == 'today price-suggestion-undefined' || $class == 'tile-previous') {
						if ($rooms_price->status($date) == 'Not available' && $check_valid_date == true) {
							$class = 'status-b';
							$available_class = 'available-date';
							$bottom_green = '';
						}
					}

					$current_year = date('Y', strtotime($date));
					$next_year_class = '';
					if ($year != $current_year && !$year_changed) {
						$year_changed = true;
						$next_year_class = 'next-year';

						if ($i < 6) {
							//$total_blank_i = 6 - $i;
							for ($blank_i = 0; $blank_i < (7 - $i); $blank_i++) {
								$out .= '<li class="tile  no-tile-status both"> <div class="date">' . $i . '</div></li>';
							}

							$out .= '<li class="new-year"><h4>' . $current_year . '</h4></li>';

							for ($blank_i2 = 0; $blank_i2 < (7 - $blank_i); $blank_i2++) {
								$out .= '<li class="tile  no-tile-status both"> <div class="date">&nbsp;</div></li>';
							}

						} else {
							$out .= '<li class="tile  no-tile-status both"> <div class="date">&nbsp;</div></li>';
						}

					}
					$out .= '<li class="tile ' . $class . ' ' . $available_class . ' ' . $bottom_green . ' ' . $next_year_class . ' no-tile-status both" id="' . $date . '" data-day="' . $day . '" data-month="' . $month . '" data-year="' . $year . '"> <div class="date"><span class ="day-number"> <span>';
					$month_name = date('F', strtotime($effectiveDate));

					if ($final_day == 15) {
						$month_str = '<div class="month-name">' . $month_name . '</div>';
					} else {
						$month_str = '';
					}
					$out .= $final_day . '</span> ' . $today . ' </span> ' . $month_str . '</div>';

					if ($class != 'status-b') {
						$day_price = $currency_symbol = '';
						if ($check_valid_date) {
							$day_price = $rooms_price->price($date);
							$currency_symbol = $rooms_price->currency->symbol;
						}

						$out .= '<div class="price ' . $price_class . '" style="display: inline-flex;"> <span>' . $currency_symbol . '</span> <span>' . $day_price . '</span> </div>';
					}

					if ($rooms_price->notes($date) != '') {
						$out .= '<div class="tile-notes"><div class="va-container va-container-v va-container-h"><span class="va-middle tile-notes-text">' . $rooms_price->notes($date) . '</span></div></div>';
					}

					$day++;
				}
			}

		}

		$out .= '</ul> </div>';

		// HTML of Change calendar tiles price and status
		$out .= '</div></div>
		<div class="modal_done">
			<button type="button" data-behavior="modal-close" style="display:none">Done</button>
		</div>

        </div></div>
        <div class="col-lg-4 col-sm-12 col-xs-12" style="margin-top:40px">
        <div class="modal-content content-container" id="custom_calendar" style="display:none;top:20%">
    			<div class="panel">
    				<a data-behavior="modal-close" class="cal-close" href=""></a>
    				<div class="calendar-edit-form panel host-calendar-sidebar-item">
    					<form name="calendar-edit-form">
    						<div class="panel-header text-center panel-header-small" ng-init="segment_status = &quot;available&quot;">
    							<div class="segmented-control">
    								<label class="segmented-control__option" ng-class="(segment_status == &quot;available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot; " id="available_label">
    									<span>Available</span>
    									<input type="radio" checked="" ng-model="segment_status" value="available" class="segmented-control__input">
    								</label>
    								<label class="segmented-control__option" ng-class="(segment_status == &quot;not available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot;" id="not_available_label">
    									<span>Blocked</span>
    									<input type="radio" value="not available" ng-model="segment_status" class="segmented-control__input">
    								</label>
    							</div>
    						</div>
    						<div class="panel-body">
    							<div class="row text-muted text-center">
    								<div class="col-6">
    									<label>Start Date</label>
    								</div>
    								<div class="col-6">
    									<label>End Date</label>
    								</div>
    							</div>
    							<div class="row">
    								<div class="col-6">
    									<input type="text" class="ui-datepicker-target" id="calendar-edit-start" ng-model="calendar_edit_start_date">
    								</div>
    								<div class="col-6">
    									<input type="text" class="ui-datepicker-target" id="calendar-edit-end" ng-model="calendar_edit_end_date">
    								</div>
    							</div>
    						</div>
    						<div class="panel-body">
    							<div class="sidebar-price-container row-space-1 ">
    								<label>Price for each night</label>
    								<div class="embedded-currency">
    									<input type="text" style="padding-left: 32px;" value="" class="input-giant sidebar-price embedded-currency__input" ng-model="calendar_edit_price">
    									<span style="top: 13px; left: 36px;" class="embedded-currency__currency embedded-currency__currency--in-input">' . $rooms_price->currency->symbol . '</span>
    									<div style="font-size: 25px; line-height: normal; font-weight: bold; padding: 8px 20px; border: 1px solid rgb(196, 196, 196); width: 160px;" class="input-giant sidebar-price embedded-currency__input embedded-currency__input--invisible">
    										<span class="embedded-currency__currency">' . $rooms_price->currency->symbol . '</span>
    										<span class="clone-value"></span>
    									</div>
    								</div>
    							</div>
    							<div class="row-space-2 onboarding-dim">
    								<div>
    									<a data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" class="text-muted link-icon" onclick="return false;" ng-click="isAddNote = !isAddNote">
    										<span class="link-icon__text">Add note</span>
    										<i class="icon icon-caret-down"></i>
    									</a>
    									<textarea ng-model="notes" ng-show="isAddNote"></textarea>
    								</div>
    							</div>
    						</div>
    						<div class="panel-footer panel-footer-flex onboarding-dim ">
    							<a class="btn" data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" id="calendar_edit_cancel">Cancel</a>
    							<button type="button" class="btn btn-host" ng-click="calendar_edit_submit(&quot;' . url('manage-listing/' . $room_id . '/calendar') . '&quot;)">Save changes</button>
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
        </div>';

		return $out;
	}

	/**
	 * Get a Calendar HTML
	 *
	 * @param int $room_id  Room Id for get the Calendar data
	 * @param int $year     Year of Calendar
	 * @param int $month    Month of Calendar
	 * @return html
	 */
	public function generate_old($room_id = '', $year = '', $month = '') {
		// Get Room Price for Give Room ID
		$rooms_price = RoomsPrice::find($room_id);

		// Set and validate the supplied month/year
		if ($year == '') {
			$year = date('Y');
		}

		if ($month == '') {
			$month = date('m');
		}

		// Determine the total days in the month
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		// Set the starting day of the week
		$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
		$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day = $start_day + 1 - $date["wday"];

		$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
		$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

		// Set the previous & next month
		$prev_month = date('m', $prev_time);
		$next_month = date('m', $next_time);

		// Set the previous & next year
		$prev_year = date('Y', $prev_time);
		$next_year = date('Y', $next_time);

		// Set the current day, month & year
		$cur_day = date('j');
		$cur_year = date('Y');
		$cur_month = date('m');

		// Determine the total days in the previous month
		$prev_total_days = date('t', $prev_time);

		while ($day > 1) {
			$day -= 7;
		}

		// Begin building the calendar output
		$out = '<div class="host-calendar-container"><div class="calendar-month" style="width: 887px;">';

		$out .= //'<div class="row-space-2 deselect-on-click"> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-previous panel text-center" data-year="'.$prev_year.'" data-month="'.$prev_month.'"> <i class="icon icon-chevron-left h3"></i> </a> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-next panel text-center" data-year="'.$next_year.'" data-month="'.$next_month.'"> <i class="icon icon-chevron-right h3"></i> </a> <div class="current-month-selection"> <h2> <span>'.date('F Y', $local_date).'</span> <span> &nbsp;</span> <span class="current-month-arrow">▾</span> </h2>'.Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'calendar_dropdown', 'data-href' => url('manage-listing/'.$room_id.'/calendar')]).'<div class="spinner-next-to-month-nav">Just a moment...</div></div> </div>'
		'<div class="days-of-week deselect-on-click"> <ul class="list-layout clearfix"> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li> <li>Sun</li> </ul> </div>';

		$out .= '';
		$out .= '<div id="calendar_selection">';
		$out .= '<div class="days-container panel clearfix"> <ul class="list-unstyled">';

		$temp_total_days = $total_days;
		$temp_next_month = $next_month;
		$temp_next_year = $next_year;

		// Build the main body of the calendar
		while ($day <= 120) {
			for ($i = 0; $i < 7; $i++) {
				// Set HTML Class for Previous, Current and Next month tiles
				if ($day < $cur_day && $year <= $cur_year && $month <= $cur_month) {
					$class = 'tile-previous';
				} else if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
					$class = 'today price-suggestion-undefined';
				} else if ($year < $cur_year || $month < $cur_month) {
					$class = 'tile-previous';
				} else {
					$class = '';
				}

				if ($year > $cur_year) {
					$class = '';
				}

				// Mention Current Date as Today
				if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
					$today = '<span class="today-label">Today</span>';
				} else {
					$today = '';
				}

				if ($day > 0 && $day <= $total_days) {
					$date = $year . '-' . $month . '-' . $day;

					$final_day = $day;
				} else {
					if ($day <= 0) {
						$day_prev = $prev_total_days + $day;

						$date = $prev_year . '-' . $prev_month . '-' . $day_prev;

						$final_day = $day_prev;
					} else if ($day > $total_days) {
						$day_next = $day - $total_days;

						$date = $next_year . '-' . $next_month . '-' . $day_next;

						$final_day = $day_next;

						if ($day > $temp_total_days) {
							$day_next = $day - $temp_total_days;
							$date = $temp_next_year . '-' . $temp_next_month . '-' . $day_next;
							$final_day = $day_next;

							$next_time = mktime(12, 0, 0, $next_month + 1, 1, $next_year);
							$next_month = date('m', $next_time);
							$next_year = date('Y', $next_time);

							$day_next = $day - $temp_total_days;
							$temp_total_days += cal_days_in_month(CAL_GREGORIAN, $next_month, $next_year);
							//$day_next  = $day - $temp_total_days;
							$date = $next_year . '-' . $next_month . '-' . $day_next;
							$final_day = $day_next;
						}
					}
				}

				if ($class == '' || $class == 'today price-suggestion-undefined' || $class == 'tile-previous') {
					if ($rooms_price->status($date) == 'Not available') {
						$class = 'status-b';
					}

				}

				$out .= '<li class="tile ' . $class . ' no-tile-status both" id="' . $date . '" data-day="' . $day . '" data-month="' . $month . '" data-year="' . $year . '"> <div class="date"><span class ="day-number"> <span>';

				$out .= $final_day . '</span> ' . $today . ' </span> </div>';

				if ($class != 'status-b') {
					$out .= '<div class="price" style="display: inline-flex;"> <span>' . $rooms_price->currency->symbol . '</span> <span>' . $rooms_price->price($date) . '</span> </div>';
				}

				if ($rooms_price->notes($date) != '') {
					$out .= '<div class="tile-notes"><div class="va-container va-container-v va-container-h"><span class="va-middle tile-notes-text">' . $rooms_price->notes($date) . '</span></div></div>';
				}

				$day++;
			}
		}

		$out .= '</ul> </div>';

		// HTML of Change calendar tiles price and status
		$out .= '</div></div><div class="modal_done"><button type="button" data-behavior="modal-close">Done</button></div><div class="host-calendar-sidebar" style="left: 929px;">
                <div class="modal calendar-sub-modal hide" aria-hidden="false" style="" tabindex="-1">
                <div class="modal-table">
                <div class="modal-cell">
                <div class="modal-content content-container">
                <div class="panel">
                <a data-behavior="modal-close" class="modal-close" href=""></a>
                <div class="calendar-edit-form panel host-calendar-sidebar-item">
                <form name="calendar-edit-form">
                <div class="panel-header text-center panel-header-small" ng-init="segment_status = &quot;available&quot;">
                <div class="segmented-control">
                <label class="segmented-control__option" ng-class="(segment_status == &quot;available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot; ">
                <span>Available</span>
                <input type="radio" checked="" ng-model="segment_status" value="available" class="segmented-control__input">
                </label>
                <label class="segmented-control__option" ng-class="(segment_status == &quot;not available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot;">
                <span>Blocked</span>
                <input type="radio" value="not available" ng-model="segment_status" class="segmented-control__input">
                </label>
                </div>
                </div>
                <div class="panel-body">
                <div class="row text-muted text-center">
                <div class="col-6">
                <label>Start Date</label>
                </div>
                <div class="col-6">
                <label>End Date</label>
                </div>
                </div>
                <div class="row">
                <div class="col-6">
                <input type="text" class="ui-datepicker-target" id="calendar-edit-start" ng-model="calendar_edit_start_date">
                </div>
                <div class="col-6">
                <input type="text" class="ui-datepicker-target" id="calendar-edit-end" ng-model="calendar_edit_end_date">
                </div>
                </div>
                </div>
                <div class="panel-body">
                <div class="sidebar-price-container row-space-1 ">
                <label>Price for each night</label>
                <div class="embedded-currency">
                <input type="text" style="padding-left: 32px;" value="" class="input-giant sidebar-price embedded-currency__input" ng-model="calendar_edit_price">
                <span style="top: 13px; left: 36px;" class="embedded-currency__currency embedded-currency__currency--in-input">' . $rooms_price->currency->symbol . '</span>
                <div style="font-size: 25px; line-height: normal; font-weight: bold; padding: 8px 20px; border: 1px solid rgb(196, 196, 196); width: 160px;" class="input-giant sidebar-price embedded-currency__input embedded-currency__input--invisible">
                <span class="embedded-currency__currency">' . $rooms_price->currency->symbol . '</span>
                <span class="clone-value"></span>
                </div>
                </div>
                </div>
                <div class="row-space-2 onboarding-dim">
                <div>
                <a data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" class="text-muted link-icon" onclick="return false;" ng-click="isAddNote = !isAddNote">
                <span class="link-icon__text">Add note</span>
                <i class="icon icon-caret-down"></i>
                </a>
                <textarea ng-model="notes" ng-show="isAddNote"></textarea>
                </div>
                </div>
                </div>
                <div class="panel-footer panel-footer-flex onboarding-dim ">
                <a class="btn" data-prevent-default="true" href="' . url('manage-listing/' . $room_id . '/calendar') . '" id="calendar_edit_cancel">Cancel</a>
                <button type="submit" class="btn btn-host" ng-click="calendar_edit_submit(&quot;' . url('manage-listing/' . $room_id . '/calendar') . '&quot;)">Save changes</button>
                </div>
                </form>
                </div>
                </div>
                </div>
                </div>
                </div>
                </div>
                <div>
                </div>
                </div>
                </div>';

		return $out;
	}

	/**
	 * Get a Small Calendar HTML
	 *
	 * @param int $room_id          Room Id for get the Calendar data
	 * @param int $year             Year of Calendar
	 * @param int $month            Month of Calendar
	 * @param int $reservation_id   Reservation Id of Calendar
	 * @return html
	 */
	public function generate_small($room_id = '', $year = '', $month = '', $reservation_id = '') {
		// Get Room Price for Give Room ID
		$rooms_price = RoomsPrice::find($room_id);

		$reservation_details = Reservation::where('room_id', $room_id)->where('id', $reservation_id)->get();

		if (count($reservation_details)) {
			$dates = $this->get_days_reservation($reservation_details[0]->checkin, $reservation_details[0]->checkout);
		}

		// Set and validate the supplied month/year
		if ($year == '') {
			$year = date('Y');
		}

		if ($month == '') {
			$month = date('m');
		}

		// Determine the total days in the month
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		// Set the starting day of the week
		$start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
		$start_day = (!isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

		// Set the starting day number
		$local_date = mktime(12, 0, 0, $month, 1, $year);
		$date = getdate($local_date);
		$day = $start_day + 1 - $date["wday"];

		$prev_time = mktime(12, 0, 0, $month - 1, 1, $year);
		$next_time = mktime(12, 0, 0, $month + 1, 1, $year);

		// Set the previous & next month
		$prev_month = date('m', $prev_time);
		$next_month = date('m', $next_time);

		// Set the previous & next year
		$prev_year = date('Y', $prev_time);
		$next_year = date('Y', $next_time);

		// Set the current day, month & year
		$cur_day = date('j');
		$cur_year = date('Y');
		$cur_month = date('m');

		// Determine the total days in the previous month
		$prev_total_days = date('t', $prev_time);

		while ($day > 1) {
			$day -= 7;
		}

		// Begin building the calendar output
		$out = '<div class="responsive-calendar small"><input type="hidden" value="' . date('Y-m', $local_date) . '" id="month-dropdown_value">';

		$out .= '<div class="calendarMonthHeader"> <a class="previousMonth pull-left panel text-center " data-year="' . $prev_year . '" data-month="' . $prev_month . '" href="javascript:void(0);"> <i class="icon icon-chevron-left h3"></i> </a> <a class="nextMonth pull-left panel text-center " data-year="' . $next_year . '" data-month="' . $next_month . '" href="javascript:void(0);"> <i class="icon icon-chevron-right h3"></i> </a> <div class="select "> <div class="loading-wrapper">' . Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'month-dropdown']) . ' </div> </div> </div><div class="calendarDaysHeader text-right"> <ul class="list-layout clearfix"> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li> <li>Sun</li> </ul> </div>';

		$out .= '';
		$out .= '<div class="panel clearfix"> <ul class="list-unstyled calendarDates">';

		// Build the main body of the calendar
		while ($day <= $total_days) {
			for ($i = 0; $i < 7; $i++) {
				// Set HTML Class for Previous, Current and Next month tiles
				if ($day < $cur_day && $year <= $cur_year && $month <= $cur_month) {
					$class = 'tile-previous';
				} else if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
					$class = 'today';
				} else if ($year < $cur_year || $month < $cur_month) {
					$class = 'tile-previous';
				} else {
					$class = '';
				}

				if ($year > $cur_year) {
					$class = '';
				}

				// Mention Current Date as Today
				if ($day == $cur_day && $year == $cur_year && $month == $cur_month) {
					$today = '<span class="today-label">Today</span>';
				} else {
					$today = '';
				}

				if ($day > 0 && $day <= $total_days) {
					$date = $year . '-' . $month . '-' . $day;

					$final_day = $day;
				} else {
					if ($day <= 0) {
						$day_prev = $prev_total_days + $day;

						$date = $prev_year . '-' . $prev_month . '-' . $day_prev;

						$final_day = $day_prev;
					} else if ($day > $total_days) {
						$day_next = $day - $total_days;

						$date = $next_year . '-' . $next_month . '-' . $day_next;

						$final_day = $day_next;
					}
				}

				$date = date('Y-m-d', strtotime($date));

				if ($class == '' || $class == 'today') {
					if ($rooms_price->status($date) == 'Not available') {
						$class .= ' status-r';
					}

					if (count($reservation_details)) {
						if ($rooms_price->status($date) == 'Not available' && in_array($date, $dates)) {
							$class .= " status-r tile-status active";
						}

					}
				}

				$final_class = 'tile ' . $class . ' no-tile-status both';

				if (count($reservation_details)) {
					if ($rooms_price->status($date) == 'Not available' && in_array($date, $dates)) {
						$final_class = 'tile ' . $class;
					}

				}

				$out .= '<li class="' . $final_class . '" id="' . $date . '" data-day="' . $day . '" data-month="' . $month . '" data-year="' . $year . '"> <div class="date"><span class ="day-number"> <span>';

				$out .= $final_day . '</span> ' . $today . ' </span> </div>';

				$day++;
			}
		}

		$out .= '</ul></div>';

		// HTML of Change calendar tiles price and status
		$out .= '</div>';

		return $out;
	}

	/**
	 * Get a Calendar Month & Year Dropdown
	 *
	 * @return Month with Year
	 */
	public function year_month() {
		$year_month = array();

		for ($i = -2; $i < 35; $i++) {
			$time = strtotime("+$i months");
			$value = date('Y-m', $time);
			$label = date('F Y', $time);
			$year_month[$value] = $label;
		}
		return $year_month;
	}

	/**
	 * iCal Export
	 *
	 * @param array $request    Input values
	 * @return iCal file
	 */
	public function ical_export(Request $request) {
		$explode_id = explode('.', $request->id);

		// 1. Create new calendar
		$vCalendar = new \Eluceo\iCal\Component\Calendar(url());

		$result = Calendar::where('room_id', $explode_id[0])->get();

		foreach ($result as $row) {
			// 2. Create an event
			$vEvent = new \Eluceo\iCal\Component\Event();

			$vEvent
				->setDtStart(new \DateTime($row->date))
				->setDtEnd(new \DateTime($row->date))
				->setDescription($row->notes)
				->setNoTime(true)
				->setSummary($row->status);

			// 3. Add event to calendar
			$vCalendar->addComponent($vEvent);
		}

		// 4. Set headers
		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="listing-' . $explode_id[0] . '.ics"');

		// 5. Output
		echo $vCalendar->render();
	}

	/**
	 * Import iCal
	 *
	 * @param array $request    Input values
	 * @return redirect to Edit Calendar
	 */
	public function ical_import(Request $request) {
		// Validation for iCal import fields
		$rules = array(
			'url' => 'required|url',
			'name' => 'required',
		);

		$niceNames = array(
			'url' => 'URL',
			'name' => 'Name',
		);

		$validator = Validator::make($request->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		} else {
			$ical_data = [
				'room_id' => $request->id,
				'url' => $request->url,
				'name' => $request->name,
				'last_sync' => date('Y-m-d H:i:s'),
			];

			// Update or Create a iCal imported data
			ImportedIcal::updateOrCreate(['room_id' => $request->id, 'url' => $request->url], $ical_data);

			// Create a new instance of IcalController
			$ical = new IcalController($request->url);
			$events = $ical->events();

			// Get events from IcalController
			for ($i = 0; $i < $ical->event_count; $i++) {
				$start_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);

				$end_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTEND']);

				$days = $this->get_days($start_date, $end_date);

				// Update or Create a events
				for ($j = 0; $j < count($days) - 1; $j++) {
					$calendar_data = [
						'room_id' => $request->id,
						'date' => $days[$j],
						'notes' => @$events[$i]['DESCRIPTION'],
						'status' => 'Not available',
					];

					Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $days[$j]], $calendar_data);
				}
			}
		}

		return redirect('manage-listing/' . $request->id . '/calendar');
	}

	/**
	 * iCal Synchronization
	 *
	 * @param array $request    Input values
	 * @return redirect to Edit Calendar
	 */
	public function ical_sync(Request $request) {
		// Get all imported iCal URLs for give Room ID
		$result = ImportedIcal::where('room_id', $request->id)->get();

		foreach ($result as $row) {
			// Create a new instance of IcalController
			$ical = new IcalController($row->url);
			$events = $ical->events();

			// Get events from IcalController
			for ($i = 0; $i < $ical->event_count; $i++) {
				$start_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);

				$end_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTEND']);

				$days = $this->get_days($start_date, $end_date);

				// Update or Create a events
				for ($j = 0; $j < count($days) - 1; $j++) {
					$calendar_data = [
						'room_id' => $request->id,
						'date' => $days[$j],
						'notes' => @$events[$i]['DESCRIPTION'],
						'status' => 'Not available',
					];

					Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $days[$j]], $calendar_data);
				}
			}

			// Update last synchronization DateTime
			$imported_ical = ImportedIcal::find($row->id);

			$imported_ical->last_sync = date('Y-m-d H:i:s');

			$imported_ical->save();
		}

		return redirect('manage-listing/' . $request->id . '/calendar');
	}

	/**
	 * Get days between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate) {
		$sStartDate = gmdate("Y-m-d", $sStartDate);
		$sEndDate = gmdate("Y-m-d", $sEndDate);

		$aDays[] = $sStartDate;

		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}

	/**
	 * Get days between two dates for reservation
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days_reservation($sStartDate, $sEndDate) {
		$aDays[] = $sStartDate;

		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}
}
