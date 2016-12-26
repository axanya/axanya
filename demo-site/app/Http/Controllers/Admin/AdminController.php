<?php

/**
 * Admin Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Admin
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Admin;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Http\Start\Helpers;

class AdminController extends Controller
{
    protected $helper; // Global variable for instance of Helpers
    
    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Index View for Dashboard
     *
     * @return view index
     */
    public function index()
    {
        $data['users_count'] = User::get()->count();
        $data['reservations_count'] = Reservation::get()->count();
        $data['rooms_count'] = Rooms::get()->count();
        $data['today_users_count'] = User::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_reservations_count'] = Reservation::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_rooms_count'] = Rooms::whereDate('created_at', '=', date('Y-m-d'))->count();

        $chart = Reservation::whereYear('created_at', '<=', date('Y'))->whereYear('created_at', '>=', date('Y')-3)->where('status', 'Accepted')->get();

        $quarter1 = ['01', '02', '03'];
        $quarter2 = ['04', '05', '06'];
        $quarter3 = ['07', '08', '09'];
        $quarter4 = ['10', '11', '12'];

        $chart_array = [];

        foreach($chart as $row)
        {
            $month = date('m', strtotime($row->created_at));
            $year = date('Y', strtotime($row->created_at));

            if(in_array($month, $quarter1))
                $quarter = 1;
            if(in_array($month, $quarter2))
                $quarter = 2;
            if(in_array($month, $quarter3))
                $quarter = 3;
            if(in_array($month, $quarter4))
                $quarter = 4;

            $array['y'] = $year.' Q'.$quarter;
            $array['amount'] = $row->total;

            $chart_array[] = $array;
        }

        $data['line_chart_data'] = json_encode($chart_array);
        
        return view('admin.index', $data);
    }

    /**
     * Load Login View
     *
     * @return view login
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * Login Authentication
     *
     * @param array $request Input values
     * @return redirect     to dashboard
     */
    public function authenticate(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();
        
        if(@$admin->status != 'Inactive')
        {
            if(Auth::admin()->attempt(['username' => $request->username, 'password' => $request->password]))
            {
                return redirect()->intended('admin/dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', 'Log In Failed. Please Check Your Email/Password'); // Call flash message function
                return redirect('admin/login'); // Redirect to login page
            }
        }
        else
        {
            $this->helper->flash_message('danger', 'Log In Failed. You are Blocked by Admin.'); // Call flash message function
            return redirect('admin/login'); // Redirect to login page
        }
    }

    /**
     * Create a test admin user
     * 
     * @param array $request Input values
     */
    public function create(Request $request)
    {
        $admin = new Admin;

        $admin->username =   "admin";
        $admin->email    =   "admin@gmail.com";
        $admin->password =   bcrypt("admin123");

        $admin->save();
    }

    /**
     * Admin Logout
     */
    public function logout()
    {
        Auth::admin()->logout();

        return redirect('admin/login');
    }
}
