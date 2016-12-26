<?php

use Illuminate\Database\Seeder;

class RoomsPriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_price')->delete();
    	
        DB::table('rooms_price')->insert([
        		['room_id' => 10001, 'night' => 150 , 'week' =>500, 'month' =>1500, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
        		['room_id' => 10002, 'night' => 320 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
        		['room_id' => 10003, 'night' => 1200 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
        		['room_id' => 10004, 'night' => 350 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
        		['room_id' => 10005, 'night' => 350 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
        		['room_id' => 10006, 'night' => 250 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10007, 'night' => 90 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10008, 'night' => 120 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'EUR' ],
                ['room_id' => 10009, 'night' => 150 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10010, 'night' => 95 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'EUR' ],
                ['room_id' => 10011, 'night' => 120 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10012, 'night' => 350 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10013, 'night' => 1500 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10014, 'night' => 150 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10015, 'night' => 77 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ],
                ['room_id' => 10016, 'night' => 180 , 'week' =>0, 'month' =>0, 'cleaning' => 0, 'additional_guest' => 0, 'guests' => 0, 'security' => 0, 'weekend' => 0, 'currency_code' => 'USD' ]
        	]);
    }
}
