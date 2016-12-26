<?php

/**
 * Language Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Language
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    public $timestamps = false;

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'language';
}
