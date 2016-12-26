<?php

/**
 * Help Category Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Help Category
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'help_category';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return HelpCategory::whereStatus('Active')->get();
    }

    public function subcategory()
    {
      return $this->belongsTo('App\Models\HelpSubCategory','category_id','id');
    }
}
