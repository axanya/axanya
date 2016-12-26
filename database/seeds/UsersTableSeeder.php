<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

// 1 user
        User::insert( ['id' => 10001, 'first_name' => 'test', 'last_name' => 'user', 'email' => 'test@trioangle.com', 'password' => Hash::make('makent'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')] );

        DB::table('profile_picture')->delete();

        DB::table('users_verification')->delete();

        DB::table('profile_picture')->insert(['user_id' => 10001,'src' => 'profile_pic_1462128586.jpg', 'photo_source' => 'Local']);
        DB::table('users_verification')->insert(['user_id' => 10001, 'email' => 'yes']);

// 2 user
        User::insert( ['id' => 10002, 'first_name' => 'John', 'last_name' => 'Ivan', 'email' => 'john@gmail.com', 'password' => Hash::make('john123'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')] );

        DB::table('profile_picture')->insert(['user_id' => 10002,'src' => 'profile_pic_1462128938.jpg', 'photo_source' => 'Local']);

        DB::table('users_verification')->insert(['user_id' => 10002, 'email' => 'yes']);

// 3 user
        User::insert( ['id' => 10003, 'first_name' => 'Tony', 'last_name' => 'Ion', 'email' => 'tony@gmail.com', 'password' => Hash::make('tony123'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')] );

        DB::table('profile_picture')->insert(['user_id' => 10003,'src' => 'profile_pic_1462129889.jpg', 'photo_source' => 'Local']);

        DB::table('users_verification')->insert(['user_id' => 10003, 'email' => 'yes']);        
// 4 user
        User::insert( ['id' => 10004, 'first_name' => 'Mick', 'last_name' => 'hans', 'email' => 'mick@gmail.com', 'password' => Hash::make('mick123'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')] );

        DB::table('profile_picture')->insert(['user_id' => 10004,'src' => 'profile_pic_1462134332.jpg', 'photo_source' => 'Local']);

        DB::table('users_verification')->insert(['user_id' => 10004, 'email' => 'yes']);

    }
}
