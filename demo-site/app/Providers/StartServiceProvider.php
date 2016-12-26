<?php

/**
 * StartService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Providers;

use App;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\JoinUs;
use App\Models\Language;
use App\Models\Pages;
use App\Models\RoomType;
use App\Models\SiteSettings;
use Config;
use Illuminate\Support\ServiceProvider;
use Schema;
use Session;
use View;

class StartServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('DB_DATABASE') != '')
        {
            if (Schema::hasTable('currency'))
            {
                $this->currency();
            } // Calling Currency function

            if (Schema::hasTable('language'))
            {
                $this->language();
            } // Calling Language function

            if (Schema::hasTable('site_settings'))
            {
                $this->site_settings();
            } // Calling Site Settings function

            if (Schema::hasTable('pages'))
            {
                $this->pages();
            } // Calling Pages function

            if (Schema::hasTable('join_us'))
            {
                $this->join_us();
            } // Calling Join US function

            if (Schema::hasTable('room_type'))
            {
                $this->room_type();
            } // Calling Join US function
        }

    }


    public function currency()
    {
        // Currency code lists for footer
        $currency = Currency::where('status', '=', 'Active')->lists('code', 'code');
        View::share('currency', $currency);

        // IP based user details
        $ip     = getenv("REMOTE_ADDR");
        $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));

        // Default Currency code for footer
        if ($result['geoplugin_currencyCode'])
        {
            $default_currency = Currency::where('status', '=', 'Active')
                ->where('code', '=', $result['geoplugin_currencyCode'])
                ->first();
            if ( ! @$default_currency)
            {
                $default_currency = Currency::where('status', '=', 'Active')
                    ->where('default_currency', '=', '1')
                    ->first();
            }
        }
        else
        {
            $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
        }

        if ( ! @$default_currency)
        {
            $default_currency = Currency::where('status', '=', 'Active')->first();
        }

        Session::put('currency', $default_currency->code);
        $symbol = Currency::original_symbol($default_currency->code);
        Session::put('symbol', $symbol);

        View::share('default_currency', $default_currency);
        View::share('default_country', $result['geoplugin_countryCode']);
    }


    // Share Currency Details to whole software

    public function language()
    {
        // Language lists for footer
        $language = Language::where('status', '=', 'Active')->lists('name', 'value');
        View::share('language', $language);

        // Default Language for footer
        $default_language = Language::where('status', '=', 'Active')->where('default_language', '=', '1')->first();
        View::share('default_language', $default_language);
        if ($default_language)
        {
            Session::put('language', $default_language->value);
            App::setLocale($default_language->value);
        }
    }


    // Share Language Details to whole software

    public function site_settings()
    {
        $site_settings = SiteSettings::all();

        View::share('site_settings', $site_settings);

        if (env('DB_DATABASE') != '')
        {
            if (Schema::hasTable('admin'))
            {
                $admin_email = @Admin::find(1)->email;
                View::share('admin_email', $admin_email);
            }
        }

        $site_name             = isset($site_settings[0]) ? $site_settings[0]->value : '';
        $head_code             = isset($site_settings[1]) ? $site_settings[1]->value : '';
        $logo = isset($site_settings[2]) ? $site_settings[2]->value : 'logo.png';
        $home_logo = isset($site_settings[3]) ? $site_settings[3]->value : $logo;
        $home_video            = isset($site_settings[4]) ? $site_settings[4]->value : 'home_video.mp4';
        $favicon = isset($site_settings[5]) ? $site_settings[5]->value : 'favicon.ico';
        $email_logo = isset($site_settings[7]) ? $site_settings[7]->value : $logo;
        $home_video_webm       = isset($site_settings[8]) ? $site_settings[8]->value : $home_video;
        $footer_cover_image = isset($site_settings[9]) ? $site_settings[9]->value : $logo;
        $help_page_cover_image = isset($site_settings[10]) ? $site_settings[10]->value : $logo;
        $site_date_format = isset($site_settings[11]) ? $site_settings[11]->value : 'DD/MM/YYYY';
        $currency              = isset($site_settings[12]) ? $site_settings[12]->value : 'EUR';
        $version               = isset($site_settings[15]) ? $site_settings[15]->value : '0';

        define('SITE_NAME', $site_name);
        define('LOGO_URL', 'images/logos/' . $logo);
        define('EMAIL_LOGO_URL', 'images/logos/' . $email_logo);
        define('SITE_DATE_FORMAT', $site_date_format);
        define('PAYPAL_CURRENCY_CODE', $currency);
        define('PAYPAL_CURRENCY_SYMBOL', Currency::original_symbol($currency));

        View::share('site_name', $site_name);
        View::share('head_code', $head_code);
        View::share('logo', url(LOGO_URL));
        View::share('home_logo', url('images/logos/' . $home_logo));
        View::share('email_logo', url(EMAIL_LOGO_URL));
        View::share('favicon', url('images/logos/' . $favicon));
        View::share('logo_style', 'background:rgba(0, 0, 0, 0) url(' . url(LOGO_URL) . ') no-repeat scroll 0 0;');
        View::share('home_logo_style',
            'background:rgba(0, 0, 0, 0) url(' . url('images/logos/' . $home_logo) . ') no-repeat scroll 0 0;');
        View::share('home_video', url('uploads/video/' . $home_video));
        View::share('home_video_webm', url('uploads/video/' . $home_video_webm));
        View::share('footer_cover_image', url('images/logos/' . $footer_cover_image));
        View::share('help_page_cover_image', url('images/logos/' . $help_page_cover_image));

        View::share('site_date_format', $site_date_format);

        View::share('version', $version);

        Config::set('site_name', $site_name);

        if (isset($site_settings[14]) && $site_settings[14]->value == '')
        {
            SiteSettings::where('name', 'site_url')->update(['value' => url()]);
        }

    }


    // Share Static Pages data to whole software

    public function pages()
    {
        // Pages lists for footer
        $company_pages  = Pages::select('url', 'name')
            ->where('under', 'company')
            ->where('status', '=', 'Active')
            ->get();
        $discover_pages = Pages::select('url', 'name')
            ->where('under', 'discover')
            ->where('status', '=', 'Active')
            ->get();
        $hosting_pages  = Pages::select('url', 'name')
            ->where('under', 'hosting')
            ->where('status', '=', 'Active')
            ->get();

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
        $room_type = RoomType::active_all();
        View::share('header_room_type', $room_type);
    }

    // Share Site Settings data to whole software

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
