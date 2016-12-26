<?php

/**
 * Api Credentials Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Api Credentials
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ApiCredentials;
use App\Http\Start\Helpers;
use Validator;

class ApiCredentialsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Api Credentials
     *
     * @return redirect     to api_credentials
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = ApiCredentials::get();

            return view('admin.api_credentials', $data);
        }
        else if($request->submit)
        {
            // Api Credentials Validation Rules
            $rules = array(
                    'facebook_client_id'     => 'required',
                    'facebook_client_secret' => 'required',
                    'google_client_id'       => 'required',
                    'google_client_secret'   => 'required',
                    'google_map_key'   => 'required',
                    'google_map_server_key'   => 'required',
                    'linkedin_client_id'       => 'required',
                    'linkedin_client_secret'   => 'required',
                    'nexmo_api'              => 'required',
                    'nexmo_secret'              => 'required',
                    'nexmo_from'              => 'required',
                    );

            // Api Credentials Validation Custom Names
            $niceNames = array(
                        'facebook_client_id'     => 'Facebook Client ID',
                        'facebook_client_secret' => 'Facebook Client Secret',
                        'google_client_id'       => 'Google Client ID',
                        'google_client_secret'   => 'Google Client Secret',
                        'google_map_key'          => 'Google Map Browser Key',
                        'google_map_server_key'   => 'Google Map Server Key',
                        'linkedin_client_id'       => 'LinkedIn Client ID',
                        'linkedin_client_secret'   => 'LinkedIn Client Secret',
                        'nexmo_api'              => 'Nexmo Key',
                        'nexmo_secret'              => 'Nexmo Secret',
                        'nexmo_from'              => 'Nexmo From Number',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                ApiCredentials::where(['name' => 'client_id', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_id]);

                ApiCredentials::where(['name' => 'client_secret', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_secret]);

                ApiCredentials::where(['name' => 'client_id', 'site' => 'Google'])->update(['value' => $request->google_client_id]);

                ApiCredentials::where(['name' => 'client_secret', 'site' => 'Google'])->update(['value' => $request->google_client_secret]);

                ApiCredentials::where(['name' => 'key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_key]);

                ApiCredentials::where(['name' => 'server_key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_server_key]);

                ApiCredentials::where(['name' => 'client_id', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_id]);

                ApiCredentials::where(['name' => 'client_secret', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_secret]);

                ApiCredentials::where(['name' => 'key', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_api]);

                ApiCredentials::where(['name' => 'secret', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_secret]);

                ApiCredentials::where(['name' => 'from', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_from]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/api_credentials');
            }
        }
        else
        {
            return redirect('admin/api_credentials');
        }
    }
}
