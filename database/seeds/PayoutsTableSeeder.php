<?php

use Illuminate\Database\Seeder;

class PayoutsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payouts')->delete();

        DB::table('payouts')->insert([

        	['reservation_id' => '10001','room_id' => '10007','correlation_id' => '' ,'user_id' =>'10001' ,'user_type' => 'host','account' =>'', 'amount' =>'450','currency_code' =>'USD','status'=>'Future','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        	['reservation_id' => '10003','room_id' => '10001','correlation_id' => '' ,'user_id' =>'10001' ,'user_type' => 'host','account' =>'', 'amount' =>'150','currency_code' =>'USD','status'=>'Future','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        	['reservation_id' => '10005','room_id' => '10014','correlation_id' => '' ,'user_id' =>'10004' ,'user_type' => 'host','account' =>'', 'amount' =>'450','currency_code' =>'USD','status'=>'Future','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        	['reservation_id' => '10006','room_id' => '10001','correlation_id' => '' ,'user_id' =>'10003' ,'user_type' => 'guest','account' =>'', 'amount' =>'630','currency_code' =>'USD','status'=>'Future','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        	['reservation_id' => '10007','room_id' => '10015','correlation_id' => '' ,'user_id' =>'10001' ,'user_type' => 'host','account' =>'', 'amount' =>'539','currency_code' =>'USD','status'=>'Future','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53']
            ]);
        	
    }
}
