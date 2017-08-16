<?php

/**
 * Guest Gender Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Guest Gender
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestGender extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'guest_gender';

	public $timestamps = false;

	// Get all Active status records
	public static function active_all() {
		return GuestGender::whereStatus('Active')->get();
	}

	// Get all Active status records in lists type
	public static function dropdown() {
		$column_name = \App::getLocale() == 'iw' ? 'name_iw' : 'name';
		return GuestGender::whereStatus('Active')->orderBy('id', 'DESC')->lists($column_name, 'id');
	}

	// Get single field data by using id and field name
	public static function single_field($id, $field) {
		return GuestGender::whereId($id)->first()->$field;
	}
}
