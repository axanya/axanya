<?php

/**
 * AppService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use Schema;
use Validator;
use App\Models\SiteSettings;
use View;
use App\Http\Helper\FacebookHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration Setup for Social Media Services
        if(env('DB_DATABASE') != '') {
        if(Schema::hasTable('api_credentials'))
        {
            $google_result = DB::table('api_credentials')->where('site','Google')->get();
            $linkedin_result = DB::table('api_credentials')->where('site','LinkedIn')->get();
            $fb_result = DB::table('api_credentials')->where('site','Facebook')->get();
            $google_map_result = DB::table('api_credentials')->where('site','GoogleMap')->get();
            $nexmo_result = DB::table('api_credentials')->where('site', 'Nexmo')->get();
        
            Config::set(['services.google' => [
                    'client_id' => array_get($google_result, 0)->value,
                    'client_secret' => array_get($google_result, 1)->value,
                    'redirect' => url('/googleAuthenticate'),
                    ]
                    ]);
            Config::set(['services.linkedin' => [
                    'client_id' => array_get($linkedin_result, 0)->value,
                    'client_secret' => array_get($linkedin_result, 1)->value,
                    'redirect' => url('/linkedinConnect'),
                    ]
                    ]);

            Config::set(['facebook' => [
                    'client_id' => array_get($fb_result, 0)->value,
                    'client_secret' => $fb_result[1]->value,
                    'redirect' => url('/facebookAuthenticate'),
                    ]
                    ]);

            View::share('map_key', array_get($google_map_result, 0)->value);
            define('MAP_KEY', array_get($google_map_result, 0)->value);
            $fb = new FacebookHelper;
            View::share('fb_url', $fb->getUrlLogin());

            View::share('map_server_key', object_get(array_get($google_map_result, 1), 'value'));
            define('MAP_SERVER_KEY', object_get(array_get($google_map_result, 1), 'value'));

            if (count($nexmo_result) > 2)
            {
                View::share('nexmo_key', array_get($nexmo_result, 0)->value);
                View::share('nexmo_secret', array_get($nexmo_result, 1)->value);
                View::share('nexmo_from', array_get($nexmo_result, 2)->value);

                define('NEXMO_KEY', array_get($nexmo_result, 0)->value);
                define('NEXMO_SECRET', array_get($nexmo_result, 1)->value);
                define('NEXMO_FROM', array_get($nexmo_result, 2)->value);
            }
        }
        }

        // Custom Validation for CreditCard is Expired or Not
        Validator::extend('expires', function($attribute, $value, $parameters, $validator) 
        {
            $input    = $validator->getData();

            $expiryDate = gmdate('Ym', gmmktime(0, 0, 0, (int) array_get($input, $parameters[0]), 1, (int) array_get($input, $parameters[1])));
            
            return ($expiryDate > gmdate('Ym')) ? true : false;
        });

        // Custom Validation for CreditCard is Valid or Not
        Validator::extend('validateluhn', function($attribute, $value, $parameters) 
        {
            $str = '';
            foreach (array_reverse(str_split($value)) as $i => $c) 
            {
                $str .= $i % 2 ? $c * 2 : $c;
            }

            return array_sum(str_split($str)) % 10 === 0;
        });

        if(env('DB_DATABASE') != '') {
        // Configuration Setup for Email Settings
        if(Schema::hasTable('email_settings'))
        {
            $result = DB::table('email_settings')->get();
        
            Config::set([
                    'mail.driver'     => $result[0]->value,
                    'mail.host'       => $result[1]->value,
                    'mail.port'       => $result[2]->value,
                    'mail.from'       => ['address' => $result[3]->value,
                                          'name'    => $result[4]->value ],
                    'mail.encryption' => $result[5]->value,
                    'mail.username'   => $result[6]->value,
                    'mail.password'   => $result[7]->value
                    ]);

            $site_settings = SiteSettings::all();

            Config::set([
                    'laravel-backup.backup.name'             => $site_settings[0]->value,
                    'laravel-backup.monitorBackups.name'     => $site_settings[0]->value,
                    'laravel-backup.notifications.mail.from' => $result[3]->value,
                    'laravel-backup.notifications.mail.to'   => $result[3]->value
                    ]);
        }
        if(Schema::hasTable('site_settings'))
        {
            $site_settings = SiteSettings::all();

            Config::set([
                    'swap.providers.yahoo_finance'  => ($site_settings[6]->value == 'yahoo_finance') ? true : false,
                    'swap.providers.google_finance' => ($site_settings[6]->value == 'google_finance') ? true : false,
                    ]);
        }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local')
        {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
