<?php

/**
 * ReligiousAmenities Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    ReligiousAmenities
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReligiousAmenities extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'religious_amenities';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return ReligiousAmenities::whereStatus('Active')->get();
    }

    // Get Selected All ReligiousAmenities Data
    public static function selected($room_id)
    {
        $result = DB::select("select religious_amenities.name as name, religious_amenities.name_iw as name_iw, religious_amenities.id as id, rooms.id as status from religious_amenities left join rooms on find_in_set(religious_amenities.id, rooms.religious_amenities) and rooms.id = $room_id");
        return $result;
    }

    public static function selected_type($room_id, $type_id)
    {
        $result = DB::select("select religious_amenities.name as name, religious_amenities.name_iw as name_iw, religious_amenities.description as description, religious_amenities.id as id, rooms.id as status from religious_amenities left join rooms on find_in_set(religious_amenities.id, rooms.religious_amenities) and rooms.id = $room_id where type_id = $type_id");
        return $result;
    }
}
