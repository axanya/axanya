<?php

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_settings')->delete();

        DB::table('site_settings')->insert([
                ['name' => 'site_name', 'value' => 'Makent'],
                ['name' => 'head_code', 'value' => ''],
                ['name' => 'logo', 'value' => 'logo.png'],
                ['name' => 'home_logo', 'value' => 'home_logo.png'],
                ['name' => 'home_video', 'value' => 'home_video.mp4'],
                ['name' => 'favicon', 'value' => 'favicon.ico'],
        		['name' => 'currency_provider', 'value' => 'yahoo_finance'],
                ['name' => 'email_logo', 'value' => 'email_logo.png'],
        	]);
    }
}
