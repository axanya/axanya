<?php

/**
 * Api Credentials Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Api Credentials
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Models\ApiCredentials;
use Illuminate\Http\Request;
use Validator;

class ApiCredentialsController extends Controller {
	protected $helper; // Global variable for instance of Helpers

	public function __construct() {
		$this->helper = new Helpers;
	}

	/**
	 * Load View and Update Api Credentials
	 *
	 * @return redirect     to api_credentials
	 */
	public function index(Request $request) {
		if (!$_POST) {
			$data['result'] = ApiCredentials::get();

			return view('admin.api_credentials', $data);
		} else if ($request->submit) {
			// Api Credentials Validation Rules
			$rules = array(
				'facebook_client_id' => 'required',
				'facebook_client_secret' => 'required',
				'google_client_id' => 'required',
				'google_client_secret' => 'required',
				'google_map_key' => 'required',
				'linkedin_client_id' => 'required',
				'linkedin_client_secret' => 'required',
				'sms_key' => 'required',
			);

			// Api Credentials Validation Custom Names
			$niceNames = array(
				'facebook_client_id' => 'Facebook Client ID',
				'facebook_client_secret' => 'Facebook Client Secret',
				'google_client_id' => 'Google Client ID',
				'google_client_secret' => 'Google Client Secret',
				'google_map_key' => 'Google Map Key',
				'linkedin_client_id' => 'LinkedIn Client ID',
				'linkedin_client_secret' => 'LinkedIn Client Secret',
				'sms_key' => 'Sms Api Secret',
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				ApiCredentials::where(['name' => 'client_id', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_id]);

				ApiCredentials::where(['name' => 'client_secret', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_secret]);

				ApiCredentials::where(['name' => 'client_id', 'site' => 'Google'])->update(['value' => $request->google_client_id]);

				ApiCredentials::where(['name' => 'client_secret', 'site' => 'Google'])->update(['value' => $request->google_client_secret]);

				ApiCredentials::where(['name' => 'key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_key]);

				ApiCredentials::where(['name' => 'client_id', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_id]);

				ApiCredentials::where(['name' => 'client_secret', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_secret]);

				ApiCredentials::where(['name' => 'sms_key', 'site' => 'checkmobi'])->update(['value' => $request->sms_key]);

				$this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

				return redirect('admin/api_credentials');
			}
		} else {
			return redirect('admin/api_credentials');
		}
	}
}
