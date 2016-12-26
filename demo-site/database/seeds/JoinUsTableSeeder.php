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
            ['name' => 'facebook', 'value' => 'https://facebook.com/TrioangleTechnologies'],
            ['name' => 'google_plus', 'value' => 'https://plus.google.com/109660062973212147808'],
            ['name' => 'twitter', 'value' => 'https://twitter.com/trioangle'],
            ['name' => 'linkedin', 'value' => 'https://linkedin.com/in/trioangle-technologies-667a47120'],
            ['name' => 'pinterest', 'value' => 'https://in.pinterest.com/trioangle1/'],
            ['name' => 'youtube', 'value' => 'https://www.youtube.com/channel/UC2EWcEd5dpvGmBh-H4TQ0wg'],
            ['name' => 'instagram', 'value' => 'https://www.instagram.com/trioangletech'],
        ]);
    }
}
