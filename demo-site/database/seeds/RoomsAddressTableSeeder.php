<?php

use Illuminate\Database\Seeder;

class RoomsAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_address')->delete();
    	
        DB::table('rooms_address')->insert([
        		['room_id' => 10001 , 'address_line_1' =>'Laguna Street' , 'address_line_2' => '' , 'city' => 'San Francisco', 'state' => 'California', 'country' => 'US' , 'postal_code' => '94123', 'latitude' => '37.7965666' , 'longitude' => '-122.4305764'],
        		['room_id' => 10002, 'address_line_1' =>'6th Avenue' , 'address_line_2' => '' , 'city' => 'San Francisco', 'state' => 'California', 'country' => 'US' , 'postal_code' => '94118', 'latitude' => '37.774897' , 'longitude' => '-122.44683'],
  				  ['room_id' => 10003 , 'address_line_1' =>'Edna Street' , 'address_line_2' => '' , 'city' => 'San Francisco', 'state' => 'California', 'country' => 'US' , 'postal_code' => '94112', 'latitude' => '37.727129' , 'longitude' => '-122.44683'],
  				  ['room_id' => 10004 , 'address_line_1' =>'Cowcross Street' , 'address_line_2' => '' , 'city' => 'London', 'state' => '', 'country' => 'GB' , 'postal_code' => 'EC1M 6BY', 'latitude' => '51.5209846' , 'longitude' => '-0.10589149999998'],
  				  ['room_id' => 10005 , 'address_line_1' =>'Aylesford Street' , 'address_line_2' => '' , 'city' => 'London', 'state' => '', 'country' => 'GB' , 'postal_code' => 'SW1V 3RY', 'latitude' => '51.4868265' , 'longitude' => '-0.13334770000006'],
  				  ['room_id' => 10006 , 'address_line_1' =>'Oxford Street' , 'address_line_2' => '' , 'city' => 'London', 'state' => '', 'country' => 'GB' , 'postal_code' => 'W1C 2JX', 'latitude' => '51.5140909' , 'longitude' => '-0.14985200000001'],
            ['room_id' => 10007 , 'address_line_1' =>'East 16th Street' , 'address_line_2' => '' , 'city' => 'New York', 'state' => 'New York', 'country' => 'US' , 'postal_code' => '10009', 'latitude' => '40.7296115' , 'longitude' => '-73.973133'],
            ['room_id' => 10008 , 'address_line_1' =>'New Jersey 3' , 'address_line_2' => '' , 'city' => 'North Bergen', 'state' => 'New Jersey', 'country' => 'US' , 'postal_code' => '07047', 'latitude' => '40.7735639' , 'longitude' => '-74.0423642'],
            ['room_id' => 10009 , 'address_line_1' =>'Port Jersey Boulevard' , 'address_line_2' => '' , 'city' => 'Jersey City', 'state' => 'New Jersey', 'country' => 'US' , 'postal_code' => '07305', 'latitude' => '40.6683996' , 'longitude' => '-74.0657264'],
            ['room_id' => 10010 , 'address_line_1' =>'Rue Chardon Lagache' , 'address_line_2' => '' , 'city' => 'Paris', 'state' => 'Île-de-France', 'country' => 'FR' , 'postal_code' => '75016', 'latitude' => '48.8420392' , 'longitude' => '2.2643651999999'],
            ['room_id' => 10011 , 'address_line_1' =>'Promenade du Quai de Grenelle' , 'address_line_2' => '' , 'city' => 'Paris', 'state' => 'Île-de-France', 'country' => 'FR' , 'postal_code' => '75015', 'latitude' => '48.8538031' , 'longitude' => '2.2874745'],
            ['room_id' => 10012 , 'address_line_1' =>'Rue Raymond Losserand' , 'address_line_2' => '' , 'city' => 'Paris', 'state' => 'Île-de-France', 'country' => 'FR' , 'postal_code' => '75014', 'latitude' => '48.8311912' , 'longitude' => '2.3137915'],
            ['room_id' => 10013 , 'address_line_1' =>'Hilo Kona Highway' , 'address_line_2' => '' , 'city' => '', 'state' => 'Hawaii', 'country' => 'US' , 'postal_code' => '75014', 'latitude' => '19.6163893' , 'longitude' => '-155.6069344'],
            ['room_id' => 10014 , 'address_line_1' =>'Camí de Can Borrell' , 'address_line_2' => '' , 'city' => 'Sant Cugat del Vallès', 'state' => 'Catalunya', 'country' => 'ES' , 'postal_code' => '08173', 'latitude' => '41.4536999' , 'longitude' => '2.1111281999999'],
            ['room_id' => 10015 , 'address_line_1' =>'Lichtensteinallee' , 'address_line_2' => '' , 'city' => 'Berlin', 'state' => 'Berlin', 'country' => 'DE' , 'postal_code' => '10787', 'latitude' => '52.5104957' , 'longitude' => '13.3447177'],
            ['room_id' => 10016 , 'address_line_1' =>'Tigris utca' , 'address_line_2' => '' , 'city' => 'Budapest', 'state' => 'Berlin', 'country' => 'HU' , 'postal_code' => '1016', 'latitude' => '47.490574' , 'longitude' => '19.0347498']
        	]);
    }
}

