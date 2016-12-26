<?php

use Illuminate\Database\Seeder;

class JoinUsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('join_us')->delete();

        DB::table('join_us')->insert([
        		['name' => 'facebook', 'value' => 'https://www.facebook.com/makent'],
        		['name' => 'google_plus', 'value' => 'https://plus.google.com/+makent'],
        		['name' => 'twitter', 'value' => 'https://twitter.com/makent'],
        		['name' => 'linkedin', 'value' => 'https://www.linkedin.com/company/makent'],
        		['name' => 'pinterest', 'value' => 'https://www.pinterest.com/makent/'],
        		['name' => 'youtube', 'value' => 'http://www.youtube.com/makent'],
        		['name' => 'instagram', 'value' => 'https://www.instagram.com/makent'],
        	]);
    }
}
