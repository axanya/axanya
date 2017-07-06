<?php

/**
 * Users Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Users
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\UsersVerification;
use App\Http\Start\Helpers;
use Validator;

class UsersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Users
     *
     * @param array $dataTable  Instance of UsersDataTable
     * @return datatable
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.view');
    }

    /**
     * Add a New User
     *
     * @param array $request  Input values
     * @return redirect     to Users view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.users.add');
        }
        else if($request->submit)
        {
            // Add User Validation Rules
            $rules = array(
                    'first_name' => 'required',
                    'last_name'  => 'required',
                    'email'      => 'required|email|unique:users',
                    'password'   => 'required',
                    'dob'        => 'required',
                    'status'     => 'required'
                    );

            // Add User Validation Custom Names
            $niceNames = array(
                        'first_name' => 'First name',
                        'last_name'  => 'Last name',
                        'email'      => 'Email',
                        'password'   => 'Password',
                        'dob'        => 'DOB',
                        'status'     => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = new User;

                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->password   = bcrypt($request->password);
                $user->dob        = date('Y-m-d', strtotime($request->dob));
                $user->status     = $request->status;

                $user->save();

                $user_pic = new ProfilePicture;

                $user_pic->user_id      =   $user->id;
                $user_pic->src          =   "";
                $user_pic->photo_source =   'Local';

                $user_pic->save();

                $users_verification = new UsersVerification;

                $users_verification->user_id      =   $user->id;
                $users_verification->email        =   "yes";

                $users_verification->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/users');
            }
        }
        else
        {
            return redirect('admin/users');
        }
    }

    /**
     * Update User Details
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = User::find($request->id);

            return view('admin.users.edit', $data);
        }
        else if($request->submit)
        {
            // Edit User Validation Rules
            $rules = array(
                    'first_name' => 'required',
                    'last_name'  => 'required',
                    'email'      => 'required|email|unique:users,email,'.$request->id,
                    'dob'        => 'required',
                    'status'     => 'required'
                    );

            // Edit User Validation Custom Fields Name
            $niceNames = array(
                        'first_name' => 'First name',
                        'last_name'  => 'Last name',
                        'email'      => 'Email',
                        'dob'        => 'DOB',
                        'status'     => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = User::find($request->id);

                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->dob        = date('Y-m-d', strtotime($request->dob));
                $user->status     = $request->status;

                $user->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/users');
            }
        }
        else
        {
            return redirect('admin/users');
        }
    }

    /**
     * Delete User
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
    public function delete(Request $request)
    {
        User::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/users');
    }
}
