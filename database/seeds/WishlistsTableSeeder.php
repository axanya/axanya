<?php

use Illuminate\Database\Seeder;

class WishlistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wishlists')->delete();
    	
        DB::table('wishlists')->insert([
        	['user_id' => '10001','name' => 'San Francisco','privacy' => '0','pick' => 'Yes'],
  			['user_id' => '10002','name' => 'London','privacy' => '0','pick' => 'Yes'],
  			['user_id' => '10003','name' => 'New York','privacy' => '0','pick' => 'Yes'],
  			['user_id' => '10004','name' => 'Jersey City','privacy' => '0','pick' => 'Yes']
        ]);
    }
}
