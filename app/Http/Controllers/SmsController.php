<?php

/**
 * User Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper\FacebookHelper;
use App\Http\Start\Helpers;
use App\Models\ApiCredentials;
use Auth;

class SmsController extends Controller {
	protected $helper; // Global variable for Helpers instance
	private $fb; // Global variable for FacebookHelper instance

	public function __construct(FacebookHelper $fb) {
		global $sms_enable;
		global $app_mode;
		$sms_enable = true;
		$app_mode = "production"; // production or development

		$this->fb = $fb;
		$this->helper = new Helpers;
	}

	public static function check_number($number) {

		$sms_key_info = ApiCredentials::where(['id' => 8])->first();

		$sms_key = $sms_key_info->value;

		$fields = array('number' => $number);
		//open connection
		$ch = curl_init();
		$url = 'https://api.checkmobi.com/v1/checknumber';
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Accept: application/json", "Authorization: {$sms_key}"));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);
		$result = json_decode($result);

		//close connection
		curl_close($ch);

		if (isset($result->error) && $result->error != '') {
			return ["success" => false, "msg" => $result->error];
		} else {
			return ["success" => true, "msg" => "Valid number"];
		}

	}

	public static function send_sms($number, $message) {
		global $sms_enable;

		$sms_key_info = ApiCredentials::where(['id' => 8])->first();

		$sms_key = $sms_key_info->value;

		if ($sms_enable) {
			$fields = array('to' => $number,
				'text' => $message,
				'platform' => 'web');
			//open connection
			$ch = curl_init();
			$url = 'https://api.checkmobi.com/v1/sms/send';
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json", "Accept: application/json", "Authorization: {$sms_key}"));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			//execute post
			$result = curl_exec($ch);
			$result = json_decode($result);

			//close connection
			curl_close($ch);

			if (isset($result->error) && $result->error != '') {
				return ["success" => false, "msg" => $result->error];
			} else {
				return ["success" => true, "msg" => "Sms sent successfully"];
			}
		} else {
			return ["success" => true, "msg" => "Sms sent successfully"];
		}
	}
}
