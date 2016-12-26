<?php

/**
 * Profile Picture Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Profile Picture
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{

    public $timestamps = false;

    public $appends = ['header_src', 'email_src'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profile_picture';

    protected $primaryKey = 'user_id';

    // Get picture source URL based on photo_source

    public function getSrcAttribute()
    {
    	if($this->attributes['photo_source'] == 'Google')
    		$src = str_replace('50', '225', $this->attributes['src']);
        else
    		$src = $this->attributes['src'];

        if($src == '')
            $src = url('images/user_pic-225x225.png');
        else if($this->attributes['photo_source'] == 'Local')
            $src = url('images/users/'.$this->attributes['user_id'].'/'.$this->attributes['src']);

        return $src;
    }

    // Get header picture source URL based on photo_source
    public function getHeaderSrcAttribute()
    {
        if($this->attributes['photo_source'] == 'Facebook')
            $src = str_replace('large', 'small', $this->attributes['src']);
        else
            $src = $this->attributes['src'];

        if($src == '')
            $src = url('images/profile_photo.png');
        else if($this->attributes['photo_source'] == 'Local')
            $src = url('images/users/'.$this->attributes['user_id'].'/'.$this->attributes['src']);

        return $src;
    }


    public function getEmailSrcAttribute()
    {
        if ($this->attributes['photo_source'] == 'Facebook')
        {
            $src = str_replace('large', 'small', $this->attributes['src']);
        }
        else
        {
            $src = $this->attributes['src'];
        }

        $url = SiteSettings::where('name', 'site_url')->value('value');

        if ($src == '')
        {
            $src = $url . 'images/profile_photo.png';
        }
        else
        {
            if ($this->attributes['photo_source'] == 'Local')
            {
                $src = $url . 'images/users/' . $this->attributes['user_id'] . '/' . $this->attributes['src'];
            }
        }

        return $src;
    }
}
