<?php

/**
 * Coupon Code Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Coupon Code
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{

    public $timestamps = false;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupon_code';

    // Convert y-m-d date into d-m-y

    public function getExpiredAtDmyAttribute()
    {
        if(@$this->attributes['expired_at'] != '0000-00-00')
            return date('d-m-Y', strtotime(@$this->attributes['expired_at']));
        else
            return '';
    }
}
