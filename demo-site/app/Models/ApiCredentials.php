<?php

/**
 * Api Credentials Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Api Credentials
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredentials extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_credentials';
}
