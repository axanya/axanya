<?php

/**
 * Rooms Address Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Address
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class RoomsPolicies extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_policies';

    public $timestamps = false;

    protected $primaryKey = 'room_id';

    protected $appends = [];

    // Get specific fields by using given id and field name
    public static function count($id)
    {
        return RoomsBathroom::where(['room_id' => $id])->count();
    }
    
    // Delete rows using given id and rows count
    public static function delete_rows($id, $rows)
    {
        return RoomsBathroom::where(['room_id' => $id])->orderBy('id', 'DESC')->skip(0)->take($rows)->delete();
    }

    // Get number of bedrooms and its bed option using room id
    public static function get_bedroom_details($id)
    {
        $total_bedrooms = RoomsBathroom::where(['room_id' => $id])->get();
        return $total_bedrooms;                       
    }

    // Get country_name by using country code in Country table
    public function getCountryNameAttribute()
    {
        if($this->attributes['country'] != ''){
            return Country::where('short_name',$this->attributes['country'])->first()->long_name;
        }
    }

    // Get steps_count using sum of rooms_steps_status
    public function getStepsCountAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['room_id']);

        return 6 - ($result->basics + $result->description + $result->location + $result->photos + $result->pricing + $result->calendar);
    }

    // Get steps_completed using sum of rooms_steps_status
    public function getStepsCompletedAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['room_id']);

        return (@$result->basics + @$result->description + @$result->location + @$result->amenities + @$result->photos + @$result->pricing + @$result->calendar);
    }
    
}
