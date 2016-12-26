<?php

/**
 * Rooms Address Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Address
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsAddress extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_address';

    protected $primaryKey = 'room_id';

    protected $appends = ['country_name', 'steps_count'];

    // Get specific fields by using given id and field name
    public static function single_field($id, $field)
    {
        return RoomsAddress::find($id)->value($field);
    }
    
    // Get country_name by using country code in Country table
    public function getCountryNameAttribute()
    {
        return Country::where('short_name', $this->attributes['country'])->value('long_name');
    }

    // Get steps_count using sum of rooms_steps_status
    public function getStepsCountAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['room_id']);

        return 6 - ($result->basics + $result->description + $result->location + $result->photos + $result->pricing + $result->calendar);
    }
    
}
