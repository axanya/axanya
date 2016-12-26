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
            ['name' => 'Entire home/apt', 'description' => 'Guests will rent the entire place. Includes in-law units.'],
            ['name'        => 'Private room',
             'description' => 'Guests share some spaces but they’ll have their own private room for sleeping.'
            ],
            ['name' => 'Shared room', 'description' => 'Guests don’t have a room to themselves.']
        ]);
    }
}
