<?php

use Illuminate\Database\Seeder;

class EmailSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_settings')->delete();

        DB::table('email_settings')->insert([
        		['name' => 'driver', 'value' => 'smtp'],
        		['name' => 'host', 'value' => 'smtp.gmail.com'],
        		['name' => 'port', 'value' => '25'],
        		['name' => 'from_address', 'value' => 'trioangle1@gmail.com'],
        		['name' => 'from_name', 'value' => 'Makent'],
        		['name' => 'encryption', 'value' => 'tls'],
        		['name' => 'username', 'value' => 'trioangle1@gmail.com'],
        		['name' => 'password', 'value' => 'hismljhblilxdusd'],
        	]);
    }
}
