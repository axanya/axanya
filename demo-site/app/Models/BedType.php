<?php

/**
 * Bed Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Bed Type
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{

    public $timestamps = false;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bed_type';

    // Get all Active status records

    public static function active_all()
    {
    	return BedType::whereStatus('Active')->get();
    }
}
