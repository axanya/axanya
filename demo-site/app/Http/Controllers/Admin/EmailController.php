<?php

/**
 * Email Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Email
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\EmailSettings;
use App\Models\User;
use App\Http\Start\Helpers;
use Validator;
use Mail;
use App;

class EmailController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Email Settings Data
     *
     * @return redirect     to email_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = EmailSettings::get();

            return view('admin.email.email_settings', $data);
        }
        else if($request->submit)
        {
            // Email Settings Validation Rules
            $rules = array(
                    'driver'       => 'required',
                    'host'         => 'required',
                    'port'         => 'required',
                    'from_address' => 'required',
                    'from_name'    => 'required',
                    'encryption'   => 'required',
                    'username'     => 'required',
                    'password'     => 'required'
                    );

            // Email Settings Validation Custom Names
            $niceNames = array(
                        'driver'       => 'Driver',
                        'host'         => 'Host',
                        'port'         => 'Port',
                        'from_address' => 'From Address',
                        'from_name'    => 'From Name',
                        'encryption'   => 'Encryption',
                        'username'     => 'Username',
                        'password'     => 'Password'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                EmailSettings::where(['name' => 'driver'])->update(['value' => $request->driver]);
                EmailSettings::where(['name' => 'host'])->update(['value' => $request->host]);
                EmailSettings::where(['name' => 'port'])->update(['value' => $request->port]);
                EmailSettings::where(['name' => 'from_address'])->update(['value' => $request->from_address]);
                EmailSettings::where(['name' => 'from_name'])->update(['value' => $request->from_name]);
                EmailSettings::where(['name' => 'encryption'])->update(['value' => $request->encryption]);
                EmailSettings::where(['name' => 'username'])->update(['value' => $request->username]);
                EmailSettings::where(['name' => 'password'])->update(['value' => $request->password]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/email_settings');
            }
        }
        else
        {
            return redirect('admin/email_settings');
        }
    }

    public function send_email(Request $request)
    {
        if(!$_POST)
        {
            $result = User::select('email')->get();
            foreach ($result as $row)
                $results[] = $row->email;
            $data['email_address_list'] = json_encode(@$results);
            return view('admin.email.send_email', $data);
        }
        else if($request->submit)
        {
            // Send Email Validation Rules
            $rules = array(
                    'subject' => 'required',
                    'message' => 'required',
                    );

            if($request->to != 'to_all')
                $rules['email'] = 'required';

            // Send Email Validation Custom Names
            $niceNames = array(
                        'subject' => 'Subject',
                        'message' => 'Message',
                        'email'   => 'Email',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                if($request->to == 'to_all') {
                    $results = User::select('email')->get();
                    foreach ($results as $row)
                        $result[] = $row->email;
                }
                else
                    $result = explode(',', $request->email);

                $emails = @array_filter(@array_map('trim',@$result));
                $data['url'] = url().'/';
                $data['locale']       = App::getLocale();

                for($i=0; $i<count($emails); $i++)
                {
                    $user = User::where('email', $emails[$i])->get();
                    $data['first_name'] = (@$user[0]->first_name) ? $user[0]->first_name : $emails[$i];
                    $data['content'] = $request->message;
                    $subject = $request->subject;
                    Mail::queue('emails.custom_email', $data, function($message) use($user, $subject, $emails, $i)
                    {
                        $message->to((@$user[0]->email) ? $user[0]->email : $emails[$i], (@$user[0]->first_name) ? $user[0]->first_name : $emails[$i])->subject($subject);
                    });
                }
                $this->helper->flash_message('success', 'Email Sent Successfully'); // Call flash message function
                return redirect('admin/send_email');
            }
        }
    }
}
