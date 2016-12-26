<?php

use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->delete();

        DB::table('reviews')->insert([

        	['reservation_id' => '10003','room_id' => '10001','user_from' =>'10001' ,'user_to' => '10004','review_by' =>'host', 'comments' =>'its good','private_feedback' =>'its good','love_comments'=>'','improve_comments' =>'' ,'rating' => '0','accuracy' =>'0', 'accuracy_comments' =>'','cleanliness' =>'4','cleanliness_comments'=>'','checkin' => '0','checkin_comments' => '' ,'amenities' =>'0' ,'amenities_comments' => '','communication' =>'3', 'communication_comments' =>'','location' =>'0','location_comments'=>'','value' => '0','value_comments' => '','respect_house_rules' => '5' , "place_id" => "", "place" => "", "place_comments" => "", 'recommend' =>'1',	'created_at' => '2016-05-02 19:10:07','updated_at' => '2016-05-02 19:10:07'],

        	['reservation_id' => '10003','room_id' => '10001','user_from' =>'10004' ,'user_to' => '10001','review_by' =>'guest', 'comments' =>'its good','private_feedback' =>'','love_comments'=>'The environment is good','improve_comments' =>'Host need to improve language..' ,'rating' => '4','accuracy' =>'3', 'accuracy_comments' =>'its good','cleanliness' =>'3','cleanliness_comments'=>'its good','checkin' => '3','checkin_comments' => 'its good' ,'amenities' =>'5' ,'amenities_comments' => 'its good','communication' =>'3', 'communication_comments' =>'its good','location' =>'3','location_comments'=>'its good','value' => '4','value_comments' => 'its good','respect_house_rules' => '0' , "place_id" => "", "place" => "","place_comments" => "", 'recommend' =>'1',	'created_at' => '2016-05-02 19:12:07','updated_at' => '2016-05-02 19:12:07']

            ]);
    }
}
