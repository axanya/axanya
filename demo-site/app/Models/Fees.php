<?php

/**
 * Fees Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Fees
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fees';
}
