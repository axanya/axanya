<?php

/**
 * Helpers
 *
 * @package     Makent
 * @subpackage  Start
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Start;

use View;
use Session;
use App\Models\Metas;

class Helpers
{

	// Get current controller method name
	public static function meta($url, $field)
	{
		$metas = Metas::where('url', $url);

        if($metas->count())
			return $metas->value($field);
		else if($field == 'title')
			return 'Page Not Found';
		else
			return '';
	}


    // Set Flash Message function

    public function current_action($route)
    {
        $current = explode('@', $route); // Example $route value: App\Http\Controllers\HomeController@login
        View::share('current_action', $current[1]); // Share current action to all view pages
    }


    // Dynamic Function for Showing Meta Details

    public function flash_message($class, $message)
    {
        Session::flash('alert-class', 'alert-' . $class);
        Session::flash('message', $message);
    }


    public function compress_image($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
        {
            $image = imagecreatefromjpeg($source_url);
        }

        elseif ($info['mime'] == 'image/gif')
        {
            $image = imagecreatefromgif($source_url);
        }

        elseif ($info['mime'] == 'image/png')
        {
            $image = imagecreatefrompng($source_url);
        }

        imagejpeg($image, $destination_url, $quality);

        return $destination_url;
    }


    public function phone_email_remove($message)
    {
        $replacement = "[removed]";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern   = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+[\.][^\.\s]+[A-Za-z0-9\?\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

        $find    = [$email_pattern, $phone_pattern];
        $replace = [$replacement, $replacement];

        $message = preg_replace($find, $replace, $message);
        $message = preg_replace($url_pattern, $replacement, $message);

        return $message;
    }
}
