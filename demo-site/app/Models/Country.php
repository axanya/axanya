<?php

/**
 * Country Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Country
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country';

    // Join to rooms_address table

    public function rooms_address()
    {
        return $this->belongsToMany('App\Models\RoomsAddress');
    }
}
