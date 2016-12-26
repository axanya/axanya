<?php

/**
 * Our Community Banners Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurCommunityBanners extends Model
{

    public $timestamps = false;

    public $appends = ['image_url'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'our_community_banners';


    public static function active_all()
    {
        return OurCommunityBanners::whereStatus('Active')->get();
    }


    public function getImageUrlAttribute()
    {
        return url() . '/images/our_community_banners/' . $this->attributes['image'];
    }
}
