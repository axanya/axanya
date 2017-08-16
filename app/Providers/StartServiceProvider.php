<?php

/**
 * StartService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SiteSettings;
use View;
use Config;
use Schema;
use Auth;
use App;
use Session;
use App\Models\Messages;
use App\Models\Pages;
use App\Models\JoinUs;
use App\Models\RoomType;

class StartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    	if(env('DB_DATABASE') != '') {
    	if(Schema::hasTable('currency'))
        	$this->currency(); // Calling Currency function

		if(Schema::hasTable('language'))
			$this->language(); // Calling Language function

		if(Schema::hasTable('site_settings'))
			$this->site_settings(); // Calling Site Settings function

		if(Schema::hasTable('pages'))
			$this->pages(); // Calling Pages function

		if(Schema::hasTable('join_us'))
			$this->join_us(); // Calling Join US function

		if(Schema::hasTable('room_type'))
			$this->room_type(); // Calling Join US function
		}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

	// Share Currency Details to whole software
	public function currency()
	{
		// Currency code lists for footer
        $currency = Currency::where('status', '=', 'Active')->lists('code', 'code');
        View::share('currency', $currency);

		// IP based user details
        $ip = getenv("REMOTE_ADDR");
        $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));

        // Default Currency code for footer
        if($result['geoplugin_currencyCode']) {
        	$default_currency = Currency::where('status', '=', 'Active')->where('code', '=', $result['geoplugin_currencyCode'])->first();
        	if(!@$default_currency)
        		$default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
        }
        else
        	$default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();

		if(!@$default_currency)
			$default_currency = Currency::where('status', '=', 'Active')->first();

		Session::put('currency', $default_currency->code);
		$symbol = Currency::original_symbol($default_currency->code);
		Session::put('symbol', $symbol);

		View::share('default_currency', $default_currency);
		View::share('default_country', $result['geoplugin_countryCode']);
	}

	// Share Language Details to whole software
	public function language()
	{
		// Language lists for footer
        $language = Language::where('status', '=', 'Active')->lists('name', 'value');
        View::share('language', $language);

		// Default Language for footer
		$default_language = Language::where('status', '=', 'Active')->where('default_language', '=', '1')->limit(1)->get();
        View::share('default_language', $default_language);
        if($default_language->count() > 0) {
			Session::put('language', $default_language[0]->value);
			App::setLocale($default_language[0]->value);
		}
	}

	// Share Static Pages data to whole software
	public function pages()
	{
		// Pages lists for footer
        $company_pages = Pages::select('url', 'name', 'name_iw')->where('under', 'company')->where('status', '=', 'Active')->get();
        $discover_pages = Pages::select('url', 'name', 'name_iw')->where('under', 'discover')->where('status', '=', 'Active')->get();
        $hosting_pages = Pages::select('url', 'name', 'name_iw')->where('under', 'hosting')->where('status', '=', 'Active')->get();

        View::share('company_pages', $company_pages);
        View::share('discover_pages', $discover_pages);
        View::share('hosting_pages', $hosting_pages);
	}

	// Share Join Us data to whole software
	public function join_us()
	{
		$join_us = JoinUs::get();
		View::share('join_us', $join_us);
	}

	// Share Room Type data to whole software
	public function room_type()
	{
		$room_type = RoomType::get();
		View::share('header_room_type', $room_type);
	}

	// Share Site Settings data to whole software
	public function site_settings()
	{
        $site_settings = SiteSettings::all();

        View::share('site_settings', $site_settings);

		define('SITE_NAME', $site_settings[0]->value);
		define('LOGO_URL', 'images/logos/'.$site_settings[2]->value);
		define('EMAIL_LOGO_URL', 'images/logos/'.$site_settings[7]->value);

		View::share('site_name', $site_settings[0]->value);
		View::share('head_code', $site_settings[1]->value);
		View::share('logo', url('images/logos/'.$site_settings[2]->value));
		View::share('home_logo', url('images/logos/'.$site_settings[3]->value));
		View::share('email_logo', url('images/logos/'.$site_settings[7]->value));
		View::share('favicon', url('images/logos/'.$site_settings[5]->value));
		View::share('logo_style', 'background:rgba(0, 0, 0, 0) url('.url('images/logos/'.$site_settings[2]->value).') no-repeat scroll 0 0;');
		View::share('home_logo_style', 'background:rgba(0, 0, 0, 0) url('.url('images/logos/'.$site_settings[3]->value).') no-repeat scroll 0 0;');
		View::share('home_video', url('uploads/video/'.$site_settings[4]->value));

		Config::set('site_name', $site_settings[0]->value);
	}
}
