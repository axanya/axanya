<?php

use Illuminate\Database\Seeder;

class CalendarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('calendar')->delete();

        DB::table('calendar')->insert([
        		['room_id' => '10007', 'date' => '2016-06-06','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:31:48', 'updated_at' =>'2016-05-01 19:31:48'],
        		['room_id' => '10007', 'date' => '2016-06-07','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:31:48', 'updated_at' =>'2016-05-01 19:31:48'],
        		['room_id' => '10007', 'date' => '2016-06-08','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:31:48', 'updated_at' =>'2016-05-01 19:31:48'],
        		['room_id' => '10007', 'date' => '2016-06-09','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:31:48', 'updated_at' =>'2016-05-01 19:31:48'],
        		['room_id' => '10007', 'date' => '2016-06-10','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:31:48', 'updated_at' =>'2016-05-01 19:31:48'],
        		['room_id' => '10005', 'date' => '2016-05-24','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:37:48', 'updated_at' =>'2016-05-01 19:37:48'],
        		['room_id' => '10005', 'date' => '2016-05-25','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:37:48', 'updated_at' =>'2016-05-01 19:37:48'],
        		['room_id' => '10005', 'date' => '2016-05-26','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:37:48', 'updated_at' =>'2016-05-01 19:37:48'],
        		['room_id' => '10005', 'date' => '2016-05-27','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:37:48', 'updated_at' =>'2016-05-01 19:37:48'],
        		['room_id' => '10001', 'date' => '2016-05-01','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 19:55:48', 'updated_at' =>'2016-05-01 19:55:48'],
        		['room_id' => '10014', 'date' => '2016-05-17','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 20:15:48', 'updated_at' =>'2016-05-01 20:15:48'],
        		['room_id' => '10014', 'date' => '2016-05-18','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 20:15:48', 'updated_at' =>'2016-05-01 20:15:48'],
        		['room_id' => '10014', 'date' => '2016-05-19','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-01 20:15:48', 'updated_at' =>'2016-05-01 20:15:48'],
        		['room_id' => '10001', 'date' => '2016-05-16','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:15:48', 'updated_at' =>'2016-05-02 20:15:48'],
        		['room_id' => '10001', 'date' => '2016-05-17','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:15:48', 'updated_at' =>'2016-05-02 20:15:48'],
        		['room_id' => '10001', 'date' => '2016-05-18','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:15:48', 'updated_at' =>'2016-05-02 20:15:48'],
        		['room_id' => '10001', 'date' => '2016-05-19','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:15:48', 'updated_at' =>'2016-05-02 20:15:48'],
        		['room_id' => '10015', 'date' => '2016-06-14','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-15','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-16','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-17','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-18','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-19','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48'],
        		['room_id' => '10015', 'date' => '2016-06-20','price' => 0,'notes' => NULL,'status' => 'Not available','created_at' =>'2016-05-02 20:25:48', 'updated_at' =>'2016-05-02 20:25:48']
        	]);
    }
}
