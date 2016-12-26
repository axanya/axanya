<?php

use Illuminate\Database\Seeder;

class ApiCredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_credentials')->delete();
        
        DB::table('api_credentials')->insert([
                ['name' => 'client_id', 'value' => '563538487153778', 'site' => 'Facebook'],
                ['name' => 'client_secret', 'value' => 'd729ec9972c0e538bf242ba886ea9fb4', 'site' => 'Facebook'],
                ['name' => 'client_id', 'value' => '894509644009-056gvjmosch58iiuvkmdkmaa79nttqi6.apps.googleusercontent.com', 'site' => 'Google'],
                ['name' => 'client_secret', 'value' => '3D244uy989Kp2J2UKcwcYgNV', 'site' => 'Google'],
                ['name' => 'client_id', 'value' => '814qxyvczj5t7z', 'site' => 'LinkedIn'],
                ['name' => 'client_secret', 'value' => 'mkuRNAxW9TSp22Zf', 'site' => 'LinkedIn'],
                ['name' => 'key', 'value' => 'AIzaSyBXyRBiyRcEzHDJ8iU00zECMUP4cXBIm6A', 'site' => 'GoogleMap'],
            ]);
    }
}
