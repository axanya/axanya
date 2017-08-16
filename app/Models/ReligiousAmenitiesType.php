<?php

/**
 * Religious Amenities Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Religious Amenities Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReligiousAmenities;

class ReligiousAmenitiesType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'religious_amenities_type';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return ReligiousAmenitiesType::whereStatus('Active')->get();
    }

    public static function get_selected_by_type($room_id){
        $types = ReligiousAmenitiesType::active_all(); 
        $selected_type_amenities = array(); 
        foreach($types as $type){
            $key = \App::getLocale() == 'iw' ? $type->name_iw : $type->name;
            $selected_type_amenities[$key] = ReligiousAmenities::selected_type($room_id, $type->id); 
        }

        return $selected_type_amenities;
    }
}
