<?php

/**
 * Rooms Description Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Description
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsDescription extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_description';

    public $timestamps = false;

    protected $primaryKey = 'room_id';
}
