<?php

/**
 * Places Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Places
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'places';

    public $timestamps = false;

    protected $appends = ['country_name', 'reviews_count', 'reviews_count_text', 'reviews_stars', 'reviews_star_rating_div'];
    
    // Join with place_reviews table
    public function reviews()
    {
        return $this->hasMany('App\Models\PlaceReviews','place_id','id');
    }

    // Get country_name by using country code in Country table
    public function getCountryNameAttribute()
    {
        return Country::where('short_name',$this->attributes['country'])->first()->long_name;
    }

    // Get reviews_count by using 
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    // Get reviews_count_text by using 
    public function getReviewsCountTextAttribute()
    {
        $reviews_count =  $this->reviews()->count();
        return $reviews_count.' '.trans_choice('messages.header.review', $reviews_count); 
    }

    // Get reviews_stars by using 
    public function getReviewsStarsAttribute()
    {
        $review_stars_total = $this->reviews()->sum('place');
        $reviews_count = $this->reviews()->count(); 
        if($review_stars_total > 0){
            return  ($review_stars_total/$reviews_count); 
        }else{
            return 0; 
        }
    }

    // Get reviews_stars by using 
    public function getReviewsStarRatingDivAttribute()
    {
        $review_stars_total = $this->reviews()->sum('place');
        $reviews_count = $this->reviews()->count(); 
        if($review_stars_total > 0){
            $average = ($review_stars_total/$reviews_count); 
            $html  = '<div class="star-rating"> <div class="foreground">';

            $whole = floor($average);
            $fraction = $average - $whole;

            for($i=0; $i<$whole; $i++)
                $html .= ' <i class="icon icon-beach icon-star"></i>';

            if($fraction >= 0.5)
                $html .= ' <i class="icon icon-beach icon-star-half"></i>';

            $html .= ' </div> <div class="background">';
            $html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
            $html .= ' </div> </div>';
            $html .= '<div class="reviews-count" onclick="reviews_popup(event, this)" id="reviews-count-div" >'.$reviews_count.' '.trans_choice('messages.header.review', $reviews_count) .'</div>';
            return $html;
        }else{
            return ''; 
        }
    }
    
}
