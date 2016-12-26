<?php

use Illuminate\Database\Seeder;

class ReligiousAmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religious_amenities')->delete();
        DB::table('religious_amenities')->insert([
        	['type_id' => '1',	 'name' => 'Eruv',						'description' => '',						'extra_input' => ''],
        	['type_id' => '1',	 'name' => 'Hotplate',					'description' => '',						'extra_input' => ''],
        	['type_id' => '2',	 'name' => 'Dairy Sink',				'description' => '',						'extra_input' => ''],
        	['type_id' => '2',	 'name' => 'Dairy Dish Set',			'description' => '',						'extra_input' => ''],
        	['type_id' => '2',	 'name' => 'Meat Sink',					'description' => '',						'extra_input' => ''],
        	['type_id' => '2',	 'name' => 'Meat Dish Set',				'description' => '',						'extra_input' => ''],
        	['type_id' => '2',	 'name' => 'Parve Kitchen',				'description' => '',						'extra_input' => ''],
        	['type_id' => '3',	 'name' => 'Crib',						'description' => '',						'extra_input' => ''],
        	['type_id' => '3',	 'name' => 'High Chair',				'description' => '',						'extra_input' => ''],
        	['type_id' => '3',	 'name' => 'Trusted local babysitter',	'description' => 'available upon request',	'extra_input' => ''],
        	['type_id' => '4',	 'name' => 'Mikvah',					'description' => 'include distance',		'extra_input' => 'text'],
        	['type_id' => '4',	 'name' => 'Two beds',					'description' => 'built in',				'extra_input' => ''],
        	['type_id' => '4',	 'name' => 'Two beds',					'description' => 'can be arranged',			'extra_input' => ''],
        	['type_id' => '5',	 'name' => 'Chabad House',				'description' => '',						'extra_input' => ''],
        	['type_id' => '5',	 'name' => 'Reform Temple',				'description' => '',						'extra_input' => ''],
        	['type_id' => '5',	 'name' => 'Concervative Synagogue',	'description' => '',						'extra_input' => ''],
        	['type_id' => '5',	 'name' => 'Orthodox Synagogue',		'description' => '',						'extra_input' => ''],
        	['type_id' => '5',	 'name' => 'Daily Minyan Nearby',		'description' => '',						'extra_input' => ''],
		]);    
    }
}
