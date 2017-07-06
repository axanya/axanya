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

class RoomsBedOption extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_bed_option';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $appends = [];

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

    
    
}
