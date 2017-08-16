<?php

/**
 * Property Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Property Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'property_type';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return PropertyType::whereStatus('Active')->get();
    }

    // Get all Active status records in lists type
    public static function dropdown()
    {
        return PropertyType::whereStatus('Active')->lists('name','id');
    }

}
