<?php

/**
 * Password Resets Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Password Resets
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'password_resets';
}
