<?php

/**
 * Reports Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reports
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use Excel;
use App\Models\User;
use App\Models\Rooms;
use App\Models\Reservation;
use Validator;
use DB;

class ReportsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Reports
     *
     * @return view file
     */
    public function index(Request $request)
    {
        if($request->isMethod('get'))
            return view('admin.reports');
        else {
            $from = date('Y-m-d H:i:s', strtotime($request->from));
            $to = date('Y-m-d H:i:s', strtotime($request->to));
            $category = $request->category;

            if($category == '') {
                $result = User::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
                return $result;
            }
            if($category == 'rooms') {
                $result = Rooms::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
                return $result;
            }
            if($category == 'reservations') {
                $result = Reservation::where('reservation.created_at', '>=', $from)->where('reservation.created_at', '<=', $to)   ->join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at', 'reservation.*'])->get();
                return $result;
            }
        }
    }

    public function export(Request $request)
    {
        if($request->category == 'users') {
            $result = User::where('created_at', '>=', $request->from)->where('created_at', '<=', $request->to)->get();
        }
        if($request->category == 'rooms') {
            $result = Rooms::where('created_at', '>=', $request->from)->where('created_at', '<=', $request->to)->get();
        }
        if($request->category == 'reservations') {
            $result = Reservation::where('reservation.created_at', '>=', $request->from)->where('reservation.created_at', '<=', $request->to)->join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at'])->get();
        }

        Excel::create($request->category.'-report', function($excel) use($result) {
            $excel->sheet('sheet1', function($sheet) use($result) {
                $sheet->fromArray($result);
            });
        })->export('csv');
    }
}
