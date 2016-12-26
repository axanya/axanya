<?php

use Illuminate\Database\Seeder;

class RoomsStepsStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_steps_status')->delete();
    	
        DB::table('rooms_steps_status')->insert([
                ['room_id' => 10002 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
        		['room_id' => 10001 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
        		['room_id' => 10003 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
        		['room_id' => 10004 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
        		['room_id' => 10005 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
        		['room_id' => 10006 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10007 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10008 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10009 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10010 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10011 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10012 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10013 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>0, 'calendar' =>1],
                ['room_id' => 10014 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10015 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1],
                ['room_id' => 10016 , 'basics' =>1 , 'description' => 1, 'location' =>1, 'amenities' =>1, 'photos' =>1, 'pricing' =>1, 'calendar' =>1]

        	]);
    }
}
