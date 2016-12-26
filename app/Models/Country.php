<?php

/**
 * Country Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Country
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';

    public $timestamps = false;

    // Join to rooms_address table
    public function rooms_address()
    {
        return $this->belongsToMany('App\Models\RoomsAddress');
    }
}
