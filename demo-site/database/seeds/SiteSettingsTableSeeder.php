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
            ['name' => 'home_video_webm', 'value' => 'home_video.webm'],
            ['name' => 'footer_cover_image', 'value' => 'footer_cover_image.png'],
            ['name' => 'help_page_cover_image', 'value' => 'help_page_cover_image.jpg'],
            ['name' => 'site_date_format', 'value' => 'd/m/Y'],
            ['name' => 'paypal_currency', 'value' => 'EUR'],
            ['name' => 'home_page_header_media', 'value' => 'Slider'],
            ['name' => 'site_url', 'value' => ''],
            ['name' => 'version', 'value' => '1.2'],
        ]);
    }
}
