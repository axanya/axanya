<?php

/**
 * Site Settings Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Site Settings
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Models\Currency;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;
use Image;
use Artisan;
use App;

class SiteSettingsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Site Settings Data
     *
     * @return redirect     to site_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result']   = SiteSettings::get();
            $data['currency'] = Currency::where('status','Active')->lists('code', 'id');
            $data['language'] = Language::where('status','Active')->lists('name', 'id');
            $data['default_currency'] = Currency::where('default_currency',1)->first()->id;
            $data['default_language'] = Language::where('default_language',1)->first()->id;
            $data['maintenance_mode'] = (App::isDownForMaintenance()) ? 'down' : 'up';

            return view('admin.site_settings', $data);
        }
        else if($request->submit)
        {
            // Site Settings Validation Rules
            $rules = array(
                    'site_name' => 'required'
                    );

            // Site Settings Validation Custom Names
            $niceNames = array(
                        'site_name' => 'Site Name'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $image          =   $request->file('logo');
                $home_image     =   $request->file('home_logo');
                $email_image     =   $request->file('email_logo');
                $home_video     =   $request->file('home_video');
                $favicon        =   $request->file('favicon');

                if($image) {
                    $extension      =   $image->getClientOriginalExtension();
                    $filename       =   'logo' . '.' . $extension;

                    $success = $image->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    SiteSettings::where(['name' => 'logo'])->update(['value' => $filename]);
                }
                
                if($home_image) {
                    $extension      =   $home_image->getClientOriginalExtension();
                    $filename       =   'home_logo' . '.' . $extension;

                    $success = $home_image->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    SiteSettings::where(['name' => 'home_logo'])->update(['value' => $filename]);
                }
                
                if($email_image) {
                    $extension      =   $email_image->getClientOriginalExtension();
                    $filename       =   'email_logo' . '.' . $extension;

                    $success = $email_image->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    SiteSettings::where(['name' => 'email_logo'])->update(['value' => $filename]);
                }

                if($favicon) {
                    $extension      =   $favicon->getClientOriginalExtension();
                    $filename       =   'favicon' . '.' . $extension;

                    $success = $favicon->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Video');

                    SiteSettings::where(['name' => 'favicon'])->update(['value' => $filename]);
                }

                if($home_video) {
                    $extension      =   $home_video->getClientOriginalExtension();
                    $filename       =   'home_video' . '.' . $extension;

                    $success = $home_video->move('uploads/video', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Video');

                    SiteSettings::where(['name' => 'home_video'])->update(['value' => $filename]);
                }

                SiteSettings::where(['name' => 'site_name'])->update(['value' => $request->site_name]);
                SiteSettings::where(['name' => 'head_code'])->update(['value' => $request->head_code]);
                SiteSettings::where(['name' => 'currency_provider'])->update(['value' => $request->currency_provider]);

                Currency::where('status','Active')->update(['default_currency'=>0]);
                Language::where('status','Active')->update(['default_language'=>0]);

                Currency::where('id', $request->default_currency)->update(['default_currency'=>1]);
                Language::where('id', $request->default_language)->update(['default_language'=>1]);

                Artisan::call($request->maintenance_mode);
                
                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/site_settings');
            }
        }
        else
        {
            return redirect('admin/site_settings');
        }
    }
}
