<?php

/**
 * Join Us Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Join Us
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinUs extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'join_us';
}
