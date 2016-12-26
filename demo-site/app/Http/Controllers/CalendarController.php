<?php

/**
 * Calendar Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Calendar
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IcalController;
use App\Models\RoomsPrice;
use App\Models\Calendar;
use App\Models\ImportedIcal;
use App\Models\Reservation;
use Validator;
use Form;

class CalendarController extends Controller
{
    public $start_day = 'monday';   // Global Variable for Start Day of Calendar

    /**
     * Get a Calendar HTML
     *
     * @param int $room_id  Room Id for get the Calendar data 
     * @param int $year     Year of Calendar
     * @param int $month    Month of Calendar
     * @return html
     */
    public function generate($room_id= '', $year = '', $month = '')
    {
        // Get Room Price for Give Room ID
        $rooms_price = RoomsPrice::find($room_id);

        // Set and validate the supplied month/year
        if ($year == '')
            $year  = date('Y');

        if ($month == '')
            $month = date('m');

        // Determine the total days in the month
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Set the starting day of the week
        $start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
        $start_day  = ( ! isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

        // Set the starting day number
        $local_date = mktime(12, 0, 0, $month, 1, $year);
        $date       = getdate($local_date);
        $day        = $start_day + 1 - $date["wday"];
        
        $prev_time  = mktime(12, 0, 0, $month-1, 1, $year);
        $next_time  = mktime(12, 0, 0, $month+1, 1, $year);
        
        // Set the previous & next month
        $prev_month = date('m', $prev_time);
        $next_month = date('m', $next_time);
        
        // Set the previous & next year
        $prev_year  = date('Y', $prev_time);
        $next_year  = date('Y', $next_time);
        
        // Set the current day, month & year
        $cur_day    = date('j');
        $cur_year   = date('Y');
        $cur_month  = date('m');
    
        // Determine the total days in the previous month
        $prev_total_days = date('t', $prev_time);

        while ($day > 1)
        {
            $day -= 7;
        }   

        // Begin building the calendar output
        $out = '<div class="host-calendar-container"><div class="calendar-month col-lg-8 col-md-12">';

        $out .= '<div class="row-space-2 deselect-on-click"> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-previous panel text-center" data-year="'.$prev_year.'" data-month="'.$prev_month.'"> <i class="icon icon-chevron-left h3"></i> </a> <a href="'.url('manage-listing/'.$room_id.'/calendar').'" class="month-nav month-nav-next panel text-center" data-year="'.$next_year.'" data-month="'.$next_month.'"> <i class="icon icon-chevron-right h3"></i> </a> <div class="current-month-selection"> <h2> <span>'.date('F Y', $local_date).'</span> <span> &nbsp;</span> <span class="current-month-arrow">▾</span> </h2>'.Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'calendar_dropdown', 'data-href' => url('manage-listing/'.$room_id.'/calendar')]).'<div class="spinner-next-to-month-nav">Just a moment...</div></div> </div><div class="days-of-week deselect-on-click"> <ul class="list-layout clearfix"> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li> <li>Sun</li> </ul> </div>';

        $out .= '';
        $out .= '<div id="calendar_selection">';
        $out .= '<div class="days-container panel clearfix"> <ul class="list-unstyled">';
        
        // Build the main body of the calendar
        while ($day <= $total_days)
        {
            for ($i = 0; $i < 7; $i++)
            {
                // Set HTML Class for Previous, Current and Next month tiles
                if($day < $cur_day && $year <= $cur_year && $month <= $cur_month)
                    $class = 'tile-previous';
                else if($day == $cur_day && $year == $cur_year && $month == $cur_month)
                    $class = 'today price-suggestion-undefined';
                else if($year < $cur_year || $month < $cur_month)
                    $class = 'tile-previous';
                else
                    $class = '';

                if($year > $cur_year)
                    $class = '';

                // Mention Current Date as Today
                if($day == $cur_day && $year == $cur_year && $month == $cur_month)
                    $today = '<span class="today-label">Today</span>';
                else
                    $today = '';

                if ($day > 0 && $day <= $total_days)
                {
                    $date      = $year.'-'.$month.'-'.$day;
                    
                    $final_day = $day;
                }
                else
                {
                    if($day <= 0)
                    {
                        $day_prev  = $prev_total_days + $day;
                        
                        $date      = $prev_year.'-'.$prev_month.'-'.$day_prev;
                        
                        $final_day = $day_prev;
                    }
                    else if($day > $total_days)
                    {
                        $day_next  = $day - $total_days;
                        
                        $date      = $next_year.'-'.$next_month.'-'.$day_next;
                        
                        $final_day = $day_next;
                    }
                }

                if($class == '' || $class == 'today price-suggestion-undefined' || $class == 'tile-previous')
                {
                    if ($rooms_price->status($date) == 'Not available')
                    {
                        $is_reservation = Reservation::whereRoomId($room_id)
                            ->whereRaw('status!="Declined"')
                            ->whereRaw('status!="Expired"')
                            ->whereRaw('status!="Cancelled"')
                            ->whereRaw('(checkin = "' . $date . '" or (checkin < "' . $date . '" and checkout > "' . $date . '")) ')
                            ->get()
                            ->count();
                        if ($is_reservation > 0)
                        {
                            $class = 'status-b tile-previous';
                        }
                        else
                        {
                            $class = 'status-b';
                        }

                    }
                }

                $out .= '<li class="tile '.$class.' no-tile-status both" id="'.$date.'" data-day="'.$day.'" data-month="'.$month.'" data-year="'.$year.'"> <div class="date"><span class ="day-number"> <span>';
                    
                $out .= $final_day.'</span> '.$today.' </span> </div>';

                $priceDisplay = ' style="display: none;"';
                if($class != 'status-b') $priceDisplay = ' style="display: inline-flex;"';
                $out .= '<div class="price"' . $priceDisplay . '> <span>' . $rooms_price->currency->original_symbol . '</span> <span>' . $rooms_price->price($date) . '</span> </div>';

                if($rooms_price->notes($date) != '')
                    $out .= '<div class="tile-notes"><div class="va-container va-container-v va-container-h"><span class="va-middle tile-notes-text">'.$rooms_price->notes($date).'</span></div></div>';

                $day++;
            }
        }

        $out .= '</ul> </div>';

        // HTML of Change calendar tiles price and status
        $out .= '</div></div><div class="host-calendar-sidebar col-lg-4 col-md-12">
                <div class="calendar-edit-form panel host-calendar-sidebar-item hide">
                <form name="calendar-edit-form">
                <div class="panel-header text-center panel-header-small" ng-init="segment_status = &quot;available&quot;">
                <div class="segmented-control">
                <label class="segmented-control__option" id="avi" ng-class="(segment_status == &quot;available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot; ">
                <span>Available</span>
                <input type="radio" checked="" name="radio" ng-model="segment_status" value="available" class="segmented-control__input">
                </label>
                <label id="unavi" class="segmented-control__option" ng-class="(segment_status == &quot;not available&quot;) ? &quot;segmented-control__option--selected&quot; : &quot;&quot;">
                <span>Blocked</span>
                <input type="radio" checked="" name="radio" value="not available" ng-model="segment_status" class="segmented-control__input">
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
                <input readonly="readonly" type="text" class="ui-datepicker-target" id="calendar-edit-start" ng-model="calendar_edit_start_date">
                </div>
                <div class="col-6">
                <input type="text" readonly="readonly" class="ui-datepicker-target" id="calendar-edit-end" ng-model="calendar_edit_end_date">
                </div>
                </div>
                </div>
                <div class="panel-body">
                <div class="sidebar-price-container row-space-1 ">
                <label>Price for each night</label>
                <div class="embedded-currency">
                <input type="number" limit-to="9" style="padding-left: 32px;" value="" class="input-giant sidebar-price embedded-currency__input" ng-model="calendar_edit_price">
                <span style="left: 9px; font-size: 19px; top: 11px;" class="embedded-currency__currency embedded-currency__currency--in-input">' . $rooms_price->currency->original_symbol . '</span>
                <div style="font-size: 25px; line-height: normal; font-weight: bold; padding: 8px 20px; border: 1px solid rgb(196, 196, 196); width: 160px;" class="input-giant sidebar-price embedded-currency__input embedded-currency__input--invisible">
                <span class="embedded-currency__currency">' . $rooms_price->currency->original_symbol . '</span>
                <span class="clone-value"></span>
                </div>
                </div>
                </div>
                <div class="row-space-2 onboarding-dim">
                <div>
                <a data-prevent-default="true" href="'.url('manage-listing/'.$room_id.'/calendar').'" class="text-muted link-icon" onclick="return false;" ng-click="isAddNote = !isAddNote">
                <span class="link-icon__text">Add note</span>
                <i class="icon icon-caret-down"></i>
                </a>
                <textarea ng-model="notes" ng-show="isAddNote"></textarea>
                </div>
                </div>
                </div>
                <div class="panel-footer panel-footer-flex onboarding-dim ">
                <a class="btn" data-prevent-default="true" href="'.url('manage-listing/'.$room_id.'/calendar').'" id="calendar_edit_cancel">Cancel</a>
                <button type="submit" class="btn btn-host" ng-click="calendar_edit_submit(&quot;'.url('manage-listing/'.$room_id.'/calendar').'&quot;)">Save changes</button>
                </div>
                </form>
                </div>
                <div> 
                </div> 
                </div>
                </div>';

        return $out;
    }


    /**
     * Get a Calendar Month & Year Dropdown
     *
     * @return Month with Year
     */
    public function year_month()
    {
        $year_month = [];

        for ($i = -2; $i < 35; $i++)
        {
            $time               = strtotime("+$i months");
            $value              = date('Y-m', $time);
            $label              = date('F Y', $time);
            $year_month[$value] = $label;
        }

        return $year_month;
    }


    /**
     * Get a Small Calendar HTML
     *
     * @param int $room_id        Room Id for get the Calendar data
     * @param int $year           Year of Calendar
     * @param int $month          Month of Calendar
     * @param int $reservation_id Reservation Id of Calendar
     *
*@return html
     */
    public function generate_small($room_id= '', $year = '', $month = '', $reservation_id = '')
    {
        // Get Room Price for Give Room ID
        $rooms_price = RoomsPrice::find($room_id);

        $reservation_details = Reservation::where('room_id', $room_id)->where('id',$reservation_id)->get();

        if(count($reservation_details))
            $dates = $this->get_days_reservation($reservation_details[0]->checkin, $reservation_details[0]->checkout);

        // Set and validate the supplied month/year
        if ($year == '')
            $year  = date('Y');

        if ($month == '')
            $month = date('m');

        // Determine the total days in the month
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Set the starting day of the week
        $start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
        $start_day  = ( ! isset($start_days[$this->start_day])) ? 0 : $start_days[$this->start_day];

        // Set the starting day number
        $local_date = mktime(12, 0, 0, $month, 1, $year);
        $date       = getdate($local_date);
        $day        = $start_day + 1 - $date["wday"];

        $prev_time  = mktime(12, 0, 0, $month-1, 1, $year);
        $next_time  = mktime(12, 0, 0, $month+1, 1, $year);

        // Set the previous & next month
        $prev_month = date('m', $prev_time);
        $next_month = date('m', $next_time);

        // Set the previous & next year
        $prev_year  = date('Y', $prev_time);
        $next_year  = date('Y', $next_time);

        // Set the current day, month & year
        $cur_day    = date('j');
        $cur_year   = date('Y');
        $cur_month  = date('m');

        // Determine the total days in the previous month
        $prev_total_days = date('t', $prev_time);

        while ($day > 1)
        {
            $day -= 7;
        }

        // Begin building the calendar output
        $out = '<div class="responsive-calendar small"><input type="hidden" value="'.date('Y-m', $local_date).'" id="month-dropdown_value">';

        $out .= '<div class="calendarMonthHeader"> <a class="previousMonth pull-left panel text-center " data-year="'.$prev_year.'" data-month="'.$prev_month.'" href="javascript:void(0);"> <i class="icon icon-chevron-left h3"></i> </a> <a class="nextMonth pull-left panel text-center " data-year="'.$next_year.'" data-month="'.$next_month.'" href="javascript:void(0);"> <i class="icon icon-chevron-right h3"></i> </a> <div class="select "> <div class="loading-wrapper">'. Form::select('year_month', $this->year_month(), date('Y-m', $local_date), ['id' => 'month-dropdown']).' </div> </div> </div><div class="calendarDaysHeader text-right"> <ul class="list-layout clearfix"> <li>Mon</li> <li>Tue</li> <li>Wed</li> <li>Thu</li> <li>Fri</li> <li>Sat</li> <li>Sun</li> </ul> </div>';

        $out .= '';
        $out .= '<div class="panel clearfix"> <ul class="list-unstyled calendarDates">';

        // Build the main body of the calendar
        while ($day <= $total_days)
        {
            for ($i = 0; $i < 7; $i++)
            {
                // Set HTML Class for Previous, Current and Next month tiles
                if($day < $cur_day && $year <= $cur_year && $month <= $cur_month)
                    $class = 'tile-previous';
                else if($day == $cur_day && $year == $cur_year && $month == $cur_month)
                    $class = 'today';
                else if($year < $cur_year || $month < $cur_month)
                    $class = 'tile-previous';
                else
                    $class = '';

                if($year > $cur_year)
                    $class = '';

                // Mention Current Date as Today
                if($day == $cur_day && $year == $cur_year && $month == $cur_month)
                    $today = '<span class="today-label">Today</span>';
                else
                    $today = '';

                if ($day > 0 && $day <= $total_days)
                {
                    $date      = $year.'-'.$month.'-'.$day;

                    $final_day = $day;
                }
                else
                {
                    if($day <= 0)
                    {
                        $day_prev  = $prev_total_days + $day;

                        $date      = $prev_year.'-'.$prev_month.'-'.$day_prev;

                        $final_day = $day_prev;
                    }
                    else if($day > $total_days)
                    {
                        $day_next  = $day - $total_days;

                        $date      = $next_year.'-'.$next_month.'-'.$day_next;

                        $final_day = $day_next;
                    }
                }

                $date = date('Y-m-d',strtotime($date));

                if($class == '' || $class == 'today')
                {
                    if($rooms_price->status($date) == 'Not available')
                        $class .= ' status-r';

                    if(count($reservation_details))
                    {
                        if($rooms_price->status($date) == 'Not available' && in_array($date, $dates))
                        $class .= " status-r tile-status active";
                    }
                }

                $final_class = 'tile '.$class.' no-tile-status both';

                if(count($reservation_details))
                {
                if($rooms_price->status($date) == 'Not available' && in_array($date, $dates))
                    $final_class = 'tile '.$class;
                }

                $out .= '<li class="'.$final_class.'" id="'.$date.'" data-day="'.$day.'" data-month="'.$month.'" data-year="'.$year.'"> <div class="date"><span class ="day-number"> <span>';

                $out .= $final_day.'</span> '.$today.' </span> </div>';

                $day++;
            }
        }

        $out .= '</ul></div>';

        // HTML of Change calendar tiles price and status
        $out .= '</div>';

        return $out;
    }


    /**
     * Get days between two dates for reservation
     *
     * @param date $sStartDate Start Date
     * @param date $sEndDate   End Date
     *
     * @return array $days      Between two dates
     */
    public function get_days_reservation($sStartDate, $sEndDate)
    {
        $aDays[] = $sStartDate;

        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

            $aDays[] = $sCurrentDate;
        }

        return $aDays;
    }


    /**
     * iCal Export
     *
     * @param array $request    Input values
     * @return iCal file
     */
    public function ical_export(Request $request)
    {
        $explode_id = explode('.', $request->id);

        // 1. Create new calendar
        $vCalendar  = new \Eluceo\iCal\Component\Calendar(url());

        $result = Calendar::where('room_id', $explode_id[0])->get();

        foreach($result as $row)
        {
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
        header('Content-Disposition: attachment; filename="listing-'.$explode_id[0].'.ics"');

        // 5. Output
        echo $vCalendar->render();
    }


    /**
     * Import iCal
     *
     * @param array $request    Input values
     * @return redirect to Edit Calendar
     */
    public function ical_import(Request $request)
    {
        // Validation for iCal import fields
        $rules = array(
                'url'  => 'required|url',
                'name' => 'required'
                );

        $niceNames = array(
                    'url'  => 'URL',
                    'name' => 'Name'
                    );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }
        else
        {
            $ical_data = [
                    'room_id'   => $request->id,
                    'url'       => $request->url,
                    'name'      => $request->name,
                    'last_sync' => date('Y-m-d H:i:s')
                    ];

            // Update or Create a iCal imported data
            ImportedIcal::updateOrCreate(['room_id' => $request->id, 'url' => $request->url], $ical_data);

            // Create a new instance of IcalController
            $ical = new IcalController($request->url);
            $events= $ical->events();

            // Get events from IcalController
            for($i=0; $i<$ical->event_count; $i++)
            {
                $start_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);

                $end_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTEND']);

                $days = $this->get_days($start_date, $end_date);

                // Update or Create a events
                for($j=0; $j<count($days)-1; $j++)
                {
                    $calendar_data = [
                                'room_id' => $request->id,
                                'date'    => $days[$j],
                                'notes'   => @$events[$i]['DESCRIPTION'],
                                'status'  => 'Not available'
                                ];

                    Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $days[$j]], $calendar_data);
                }
            }
        }

        return redirect('manage-listing/'.$request->id.'/calendar');
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
        $sStartDate = gmdate("Y-m-d", $sStartDate);
        $sEndDate   = gmdate("Y-m-d", $sEndDate);

        $aDays[] = $sStartDate;

        $sCurrentDate = $sStartDate;

        while ($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

            $aDays[] = $sCurrentDate;
        }

        return $aDays;
    }


    /**
     * iCal Synchronization
     *
     * @param array $request    Input values
     * @return redirect to Edit Calendar
     */
    public function ical_sync(Request $request)
    {
        // Get all imported iCal URLs for give Room ID
        $result = ImportedIcal::where('room_id', $request->id)->get();

        foreach($result as $row)
        {
            // Create a new instance of IcalController
            $ical   = new IcalController($row->url);
            $events = $ical->events();

            // Get events from IcalController
            for($i=0; $i<$ical->event_count; $i++)
            {
                $start_date = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);

                $end_date   = $ical->iCalDateToUnixTimestamp($events[$i]['DTEND']);

                $days       = $this->get_days($start_date, $end_date);

                // Update or Create a events
                for($j=0; $j<count($days)-1; $j++)
                {
                    $calendar_data = [
                                'room_id' => $request->id,
                                'date'    => $days[$j],
                                'notes'   => @$events[$i]['DESCRIPTION'],
                                'status'  => 'Not available'
                                ];

                    Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $days[$j]], $calendar_data);
                }
            }

            // Update last synchronization DateTime
            $imported_ical = ImportedIcal::find($row->id);

            $imported_ical->last_sync = date('Y-m-d H:i:s');

            $imported_ical->save();
        }

        return redirect('manage-listing/'.$request->id.'/calendar');
    }
}
