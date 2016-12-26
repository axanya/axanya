<?php

/**
 * Helpers
 *
 * @package     Makent
 * @subpackage  Start
 * @category    Helper
 * @author      Trioangle Product Team
  * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Start;

use View;
use Session;
use App\Models\Metas;

class Helpers
{

	// Get current controller method name
	public function current_action($route)
	{
		$current = explode('@', $route); // Example $route value: App\Http\Controllers\HomeController@login
		View::share('current_action',$current[1]); // Share current action to all view pages
	}

	// Set Flash Message function
	public function flash_message($class, $message)
	{
		Session::flash('alert-class', 'alert-'.$class);
	    Session::flash('message', $message);
	}

	// Dynamic Function for Showing Meta Details
	public static function meta($url, $field)
	{
		$metas = Metas::where('url', $url);
	
		if($metas->count())
			return $metas->first()->$field;
		else if($field == 'title')
			return 'Page Not Found';
		else
			return '';
	}
}
