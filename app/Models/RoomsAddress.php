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

class RoomsAddress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_address';

    public $timestamps = false;

    protected $primaryKey = 'room_id';

    protected $appends = ['country_name','steps_count', 'steps_completed'];

    // Get specific fields by using given id and field name
    public static function single_field($id, $field)
    {
        return RoomsAddress::find($id)->first()->$field;
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
