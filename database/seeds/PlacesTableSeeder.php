<?php

use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('places')->delete();

        DB::table('places')->insert([
        		['id' => '1','name' => 'Kosher Vendor','type' => 'Kosher Vendor','address_line_1' => 'Laguna Street','address_line_2' => '','city' => 'San Francisco','state' => 'California','country' => 'US','postal_code' => '32352','latitude' => '37.7900000','longitude' => '-122.4305800'],
                ['id' => '2','name' => 'Restaurants','type' => 'Restaurants','address_line_1' => 'Laguna Street','address_line_2' => '','city' => 'San Francisco','state' => 'California','country' => 'US','postal_code' => '32352','latitude' => '48.5209846','longitude' => '-0.10589149999998'],
                ['id' => '3','name' => 'Mikvahs','type' => 'Mikvahs','address_line_1' => 'Laguna Street','address_line_2' => '','city' => 'San Francisco','state' => 'California','country' => 'US','postal_code' => '32352','latitude' => '38.7735639','longitude' => '-74.0423642'],
                ['id' => '4','name' => 'Synagogues','type' => 'Synagogues','address_line_1' => 'Laguna Street','address_line_2' => '','city' => 'San Francisco','state' => 'California','country' => 'US','postal_code' => '32352','latitude' => '17.6163893','longitude' => '-155.6069344']
        ]);
    }
}
