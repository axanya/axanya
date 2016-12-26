<?php

/**
 * Host Banners Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Host Banners
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostBanners extends Model
{

    public $timestamps = false;

    public $appends = ['image_url', 'link_url'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_banners';


    public function getImageUrlAttribute()
    {
        return url() . '/images/host_banners/' . $this->attributes['image'];
    }


    public function getLinkUrlAttribute()
    {
        return url() . $this->attributes['link'];
    }

}
