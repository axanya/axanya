<?php

/**
 * Home Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Home
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Http\Helper\FacebookHelper;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Help;
use App\Models\HelpSubCategory;
use App\Models\HomeCities;
use App\Models\Pages;
use App\Models\Rooms;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Route;
use Session;
use View;

class HomeController extends Controller {
	private $fb; // Global variable for store Facebook instance

	/**
	 * Constructor to Set FacebookHelper instance in Global variable
	 *
	 * @param array $fb   Instance of FacebookHelper
	 */
	public function __construct(FacebookHelper $fb) {
		$this->fb = $fb;
	}

	/**
	 * Load Home view file
	 *
	 * @return home page view
	 */
	public function index() {
		$data['popular_rooms'] = Rooms::where('status', 'Listed')->get();

		$data['home_city'] = HomeCities::all();

		$data['city_count'] = HomeCities::all()->count();
		//print_r($data['city_count']);exit;
		return view('home.home_new', $data);
	}

	public function phpinfo() {
		echo phpinfo();
	}

	/**
	 * Load Login view file with Generated Facebook login URL
	 *
	 * @return Login page view
	 */
	public function login() {
		$data['fb_url'] = $this->fb->getUrlLogin();

		return view('home.login', $data);
	}

	/**
	 * Load Social OR Email Signup view file with Generated Facebook login URL
	 *
	 * @return Signup page view
	 */
	public function signup_login(Request $request) {

		/*$fields = array('to' => '+919926957522',
								'text' => 'welcome',
								'platform' => 'web');
					//open connection
			    $ch = curl_init();
			    $url = 'https://api.checkmobi.com/v1/sms/send';
			    //set the url, number of POST vars, POST data
			    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json","Accept: application/json", "Authorization: 8444A8DB-460E-4222-8003-D6D6F49ED426"));
			    curl_setopt($ch,CURLOPT_URL, $url);
			    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			    //execute post
			    $result = curl_exec($ch);
			    echo "<pre>";print_r($result);die;
			    //close connection
		*/

		$data['class'] = '';

		$data['fb_url'] = $this->fb->getUrlLogin();
		$data['country'] = Country::where('phone_code', '!=', '0')->orderBy('long_name', 'ASC')->get();
		// Social Signup Page
		if ($request->input('sm') == 1 || $request->input('sm') == '') {
			Session::put('referral', $request->referral);
			return view('home.signup_login', $data);
		}

		// Email Signup Page
		else if ($request->input('sm') == 2) {
			return view('home.signup_login_2', $data);
		} else {
			abort(500);
		}
		// Call 500 error page
	}

	/**
	 * Set session for Currency & Language while choosing footer dropdowns
	 *
	 */
	public function set_session(Request $request) {
		if ($request->currency) {
			Session::put('currency', $request->currency);
			$symbol = Currency::original_symbol($request->currency);
			Session::put('symbol', $symbol);
		} else if ($request->language) {
			Session::put('language', $request->language);
			App::setLocale($request->language);
		}
	}

	/**
	 * View Cancellation Policies
	 *
	 * @return Cancellation Policies view file
	 */
	public function cancellation_policies() {
		return view('home.cancellation_policies');
	}

	/**
	 * View Static Pages
	 *
	 * @param array $request  Input values
	 * @return Static page view file
	 */
	public function static_pages(Request $request) {
		$pages = Pages::where(['url' => $request->name, 'status' => 'Active']);

		if (!$pages->count()) {
			abort('404');
		}

		$pages = $pages->first();

		$data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url()], $pages->content);
		$data['title'] = $pages->name;

		return view('home.static_pages', $data);
	}

	public function help(Request $request) {
		if (Route::current()->uri() == 'help') {
			$data['result'] = Help::whereSuggested('yes')->whereStatus('Active')->get();
		} elseif (Route::current()->uri() == 'help/topic/{id}/{category}') {
			$count_result = HelpSubCategory::find($request->id);
			$data['subcategory_count'] = $count = (str_slug($count_result->name, '-') != $request->category) ? 0 : 1;
			$data['is_subcategory'] = (str_slug($count_result->name, '-') == $request->category) ? 'yes' : 'no';
			if ($count) {
				$data['result'] = Help::whereSubcategoryId($request->id)->whereStatus('Active')->get();
			} else {
				$data['result'] = Help::whereCategoryId($request->id)->whereStatus('Active')->get();
			}

		} else {
			$data['result'] = Help::whereId($request->id)->whereStatus('Active')->get();
			$data['is_subcategory'] = ($data['result'][0]->subcategory_id) ? 'yes' : 'no';
		}

		$data['category'] = Help::with(['category', 'subcategory'])->whereStatus('Active')->groupBy('category_id')->get(['category_id', 'subcategory_id']);

		return view('home.help', $data);
	}

	public function ajax_help_search(Request $request) {
		$term = $request->term;

		$queries = Help::where('question', 'like', '%' . $term . '%')->get();

		foreach ($queries as $query) {
			$results[] = ['id' => $query->id, 'value' => str_replace('SITE_NAME', SITE_NAME, $query->question), 'question' => str_slug($query->question, '-')];
		}
		return json_encode($results);
	}

	public function contact_us(Request $request) {

		return view('home.contact');
	}

	public function store(Request $request) {

		// echo '<pre>';
		// print_r( $_POST );
		$data['name'] = $request->get('name');
		$data['email'] = $request->get('email');
		$data['user_message'] = $request->get('message');
		//$admin_email = 'admin@axanya.com';
		$admin_email = 'axanyaweb@gmail.com';

		\Mail::send('emails.contact_confirm',
			$data, function ($message) use ($data) {
				$message->from('admin@axanya.com');
				$message->to($_POST['email'], 'Admin')->subject('Thanks for contact Us - Axanya');
			});

		\Mail::send('emails.contact_info',
			$data, function ($message) use ($data, $admin_email) {
				$message->from('admin@axanya.com');
				$message->to($admin_email, 'Admin')->subject('Axanya - Contact Us');
			});

		return \Redirect::route('contact')->with('message', 'Your message has been sent. Thanks for contacting us!');

	}

}
