<?php

/**
 * Room Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Room Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'room_type';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return RoomType::whereStatus('Active')->get();
    }

    // Get all Active status records in lists type
    public static function dropdown()
    {
      $column_name = \App::getLocale() == 'iw' ? 'name_iw' : 'name';
        return RoomType::whereStatus('Active')->lists($column_name,'id');
    }

    // Get single field data by using id and field name
    public static function single_field($id, $field)
    {
        return RoomType::whereId($id)->first()->$field;
    }
}
