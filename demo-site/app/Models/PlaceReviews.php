<?php

/**
 * Place Reviews Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Place Reviews
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceReviews extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'place_reviews';

    protected $appends = ['updated_mdsy', 'place_review_stars_div'];

    // Join with users table
    public function users_from()
    {
      return $this->belongsTo('App\Models\User','user_from','id');
    }

    public function getUpdatedMdsyAttribute(){
        return date('F dS Y', strtotime($this->attributes['updated_at']));
    }

    public function getPlaceReviewStarsDivAttribute(){
        $average = $this->attributes['place']; 
        if($average > 0){
            $html  = '<div class="star-rating place_review_rating_div"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';

            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div></div>';
            $html .= '<div class="place_review_date" >'.$this->getUpdatedMdsyAttribute().'</div>';
            return $html;
        }else{
            return ''; 
        }
    }
   

}
