<?php

/**
 * Slider Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{

    public $timestamps = false;

    public $appends = ['image_url'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slider';


    public function getImageUrlAttribute()
    {
        return url() . '/images/slider/' . $this->attributes['image'];
    }
}
