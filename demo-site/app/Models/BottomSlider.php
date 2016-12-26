<?php

/**
 * Bottom Slider Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Bottom Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BottomSlider extends Model
{

    public $timestamps = false;

    public $appends = ['image_url'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bottom_slider';


    public function getImageUrlAttribute()
    {
        return url() . '/images/bottom_slider/' . $this->attributes['image'];
    }
}
