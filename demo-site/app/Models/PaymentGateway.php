<?php

/**
 * Payment Gateway Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Payment Gateway
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateway';
}
