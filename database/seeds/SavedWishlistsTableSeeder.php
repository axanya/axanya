<?php

use Illuminate\Database\Seeder;

class SavedWishlistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('saved_wishlists')->delete();
    	
        DB::table('saved_wishlists')->insert([
        	['user_id' => '10001','room_id' => '10004','wishlist_id' => '1'],
  			['user_id' => '10002','room_id' => '10004','wishlist_id' => '2'],
  			['user_id' => '10003','room_id' => '10007','wishlist_id' => '3'],
  			['user_id' => '10004','room_id' => '10009','wishlist_id' => '4']
        ]);
    }
}
