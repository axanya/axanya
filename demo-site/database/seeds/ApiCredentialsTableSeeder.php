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
            ['name' => 'client_id', 'value' => '673748312762563', 'site' => 'Facebook'],
            ['name' => 'client_secret', 'value' => '92913e1e346f2627d7b15828204b3afa', 'site' => 'Facebook'],
            ['name'  => 'client_id',
             'value' => '283096280211-g20vu2cl5ifr16r6bk0ksrh93d6j45pd.apps.googleusercontent.com',
             'site'  => 'Google'
            ],
            ['name' => 'client_secret', 'value' => 'ydvv1ssEhwI-jSdaG0o4BuYL', 'site' => 'Google'],
                ['name' => 'client_id', 'value' => '814qxyvczj5t7z', 'site' => 'LinkedIn'],
                ['name' => 'client_secret', 'value' => 'mkuRNAxW9TSp22Zf', 'site' => 'LinkedIn'],
                ['name' => 'key', 'value' => 'AIzaSyBXyRBiyRcEzHDJ8iU00zECMUP4cXBIm6A', 'site' => 'GoogleMap'],
            ['name' => 'server_key', 'value' => 'AIzaSyBOQGklqYyIk_F2PWZ2-Aj07MDYhafqAZg', 'site' => 'GoogleMap'],
            ['name' => 'key', 'value' => 'd7b78816', 'site' => 'Nexmo'],
            ['name' => 'secret', 'value' => '99a1dde9a6079c4a', 'site' => 'Nexmo'],
            ['name' => 'from', 'value' => 'Nexmo', 'site' => 'Nexmo'],
            ]);
    }
}
