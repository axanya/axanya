<?php

/**
 * Amenities Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Amenities Type
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenitiesType extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amenities_type';

    // Get all Active status records

    public static function active_all()
    {
    	return AmenitiesType::whereStatus('Active')->get();
    }
}
