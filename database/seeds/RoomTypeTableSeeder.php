<?php

use Illuminate\Database\Seeder;

class RoomTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_type')->delete();
    	
        DB::table('room_type')->insert([
        		['name' => 'Entire home/apt','description' => 'Entire home/apt'],
  				['name' => 'Private room','description' => 'Private room'],
  				['name' => 'Shared room','description' => 'Shared room']
        	]);
    }
}
