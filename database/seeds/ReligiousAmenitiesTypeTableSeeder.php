<?php

use Illuminate\Database\Seeder;

class ReligiousAmenitiesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religious_amenities_type')->delete();
        DB::table('religious_amenities_type')->insert([
    		['name' => 'Shabbat',		'description' => ''],
    		['name' => 'Kosher',		'description' => ''],
    		['name' => 'Family',		'description' => ''],
    		['name' => 'Intimacy',		'description' => ''],
    		['name' => 'Spirituality',	'description' => 'within walking distance'],
    	]);
    }
}
