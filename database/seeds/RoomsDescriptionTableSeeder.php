<?php

use Illuminate\Database\Seeder;

class RoomsDescriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_description')->delete();
    	
        DB::table('rooms_description')->insert([
        		['room_id' => 10001 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
        		['room_id' => 10002 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
        		['room_id' => 10003 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
        		['room_id' => 10004 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
        		['room_id' => 10005 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
        		['room_id' => 10006 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10007 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10008 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10009 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10010 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10011 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10012 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10013 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10014 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10015 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => ''],
                ['room_id' => 10016 , 'space' => '' , 'access' => '', 'interaction' => '', 'notes' => '', 'house_rules' => '' , 'neighborhood_overview' => '', 'transit' => '']



        	]);
    }
}
