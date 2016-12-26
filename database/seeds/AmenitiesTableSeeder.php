<?php

use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amenities')->delete();
    	
        DB::table('amenities')->insert([
        		['type_id' => '1', 'name' => 'Essentials','description' => 'Essentials','icon' => 'essentials'],
        		['type_id' => '1', 'name' => 'TV','description' => '','icon' => 'tv'],
        		['type_id' => '1', 'name' => 'Cable TV','description' => '','icon' => 'desktop'],
        		['type_id' => '1', 'name' => 'Air Conditioning ','description' => '','icon' => 'air-conditioning'],
        		['type_id' => '1', 'name' => 'Heating','description' => 'Heating','icon' => 'heating'],
        		['type_id' => '1', 'name' => 'Kitchen','description' => 'Kitchen','icon' => 'meal'],
        		['type_id' => '1', 'name' => 'Internet','description' => 'Internet','icon' => 'internet'],
        		['type_id' => '1', 'name' => 'Wireless Internet','description' => 'Wireless Internet','icon' => 'wifi'],
        		['type_id' => '2', 'name' => 'Hot Tub','description' => '','icon' => 'hot-tub'],
        		['type_id' => '2', 'name' => 'Washer','description' => 'Washer','icon' => 'washer'],
        		['type_id' => '2', 'name' => 'Pool','description' => 'Pool','icon' => 'pool'],
        		['type_id' => '2', 'name' => 'Dryer','description' => 'Dryer','icon' => 'dryer'],
        		['type_id' => '2', 'name' => 'Breakfast','description' => 'Breakfast','icon' => 'cup'],
        		['type_id' => '2', 'name' => 'Free Parking on Premises','description' => '','icon' => 'parking'],
        		['type_id' => '2', 'name' => 'Gym','description' => 'Gym','icon' => 'gym'],
        		['type_id' => '2', 'name' => 'Elevator in Building','description' => '','icon' => 'elevator'],
        		['type_id' => '2', 'name' => 'Indoor Fireplace','description' => '','icon' => 'fireplace'],
        		['type_id' => '2', 'name' => 'Buzzer/Wireless Intercom','description' => '','icon' => 'intercom'],
        		['type_id' => '2', 'name' => 'Doorman','description' => '','icon' => 'doorman'],
        		['type_id' => '2', 'name' => 'Shampoo','description' => '','icon' => 'smoking'],
        		['type_id' => '3', 'name' => 'Family/Kid Friendly','description' => 'Family/Kid Friendly','icon' => 'family'],
        		['type_id' => '3', 'name' => 'Smoking Allowed','description' => '','icon' => 'smoking'],
        		['type_id' => '3', 'name' => 'Suitable for Events','description' => 'Suitable for Events','icon' => 'balloons'],
        		['type_id' => '3', 'name' => 'Pets Allowed','description' => '','icon' => 'paw'],
        		['type_id' => '3', 'name' => 'Pets live on this property','description' => '','icon' => 'ok'],
        		['type_id' => '3', 'name' => 'Wheelchair Accessible','description' => 'Wheelchair Accessible','icon' => 'accessible'],
        		['type_id' => '4', 'name' => 'Smoke Detector','description' => 'Smoke Detector','icon' => 'ok'],
        		['type_id' => '4', 'name' => 'Carbon Monoxide Detector','description' => 'Carbon Monoxide Detector','icon' => 'ok'],
        		['type_id' => '4', 'name' => 'First Aid Kit','description' => '','icon' => 'ok'],
        		['type_id' => '4', 'name' => 'Safety Card','description' => 'Safety Card','icon' => 'ok'],
                ['type_id' => '4', 'name' => 'Fire Extinguisher','description' => 'Essentials','icon' => 'ok'],
          //       ['type_id' => '5', 'name' => 'Temple','description' => '','icon' => 'bell'],
        		// ['type_id' => '5', 'name' => 'church','description' => '','icon' => 'star'],
        	]);
    }
}
