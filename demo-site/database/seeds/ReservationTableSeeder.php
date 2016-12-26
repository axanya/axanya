<?php

use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        DB::table('reservation')->delete();

        DB::table('reservation')->insert([

        	['code' => 'GAEEKL','room_id' => '10007','host_id' => '10001' ,'user_id' =>'10002' ,'checkin' => '2016-06-06','checkout' =>'2016-06-11', 'number_of_guests' =>'1','nights' =>'5','per_night'=>'90','subtotal' => '450','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '23','host_fee' =>'0', 'total' =>'473','currency_code' =>'USD','transaction_id'=>'2LF35517XH822014C','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Accepted','type'=>'reservation','friends_email' => '','cancelled_by' => NULL,'cancelled_reason' => '' ,'decline_reason' =>'' ,'accepted_at' => '2016-05-01 19:05:10','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => 'T5FOSN','room_id' => '10005','host_id' => '10001' ,'user_id' =>'10003' ,'checkin' => '2016-05-24','checkout' =>'2016-05-28', 'number_of_guests' =>'1','nights' =>'4','per_night'=>'350','subtotal' => '1400','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '70','host_fee' =>'0', 'total' =>'1470','currency_code' =>'USD','transaction_id'=>'9F8124896A6245800','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Pending','type'=>'reservation','friends_email' => '','cancelled_by' => NULL,'cancelled_reason' => '' ,'decline_reason' =>'' ,'accepted_at' => '0000-00-00 00:00:00','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => 'C6O79Y','room_id' => '10001','host_id' => '10001' ,'user_id' =>'10004' ,'checkin' => '2016-09-21','checkout' =>'2016-09-22', 'number_of_guests' =>'1','nights' =>'1','per_night'=>'150','subtotal' => '150','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '8','host_fee' =>'0', 'total' =>'158','currency_code' =>'USD','transaction_id'=>'3VX25821WB639673W','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Accepted','type'=>'reservation','friends_email' => '','cancelled_by' => NULL,'cancelled_reason' => '' ,'decline_reason' =>'' ,'accepted_at' => '2016-05-01 20:05:57','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => '','room_id' => '10005','host_id' => '10001' ,'user_id' =>'10002' ,'checkin' => '2016-06-14','checkout' =>'2016-06-17', 'number_of_guests' =>'4','nights' =>'3','per_night'=>'350','subtotal' => '1050','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '53','host_fee' =>'0', 'total' =>'1103','currency_code' =>'USD','transaction_id'=>'','paymode' => NULL,'cancellation' => '' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'US','status' =>NULL,'type'=>'contact','friends_email' => '','cancelled_by' => NULL,'cancelled_reason' => '' ,'decline_reason' =>'' ,'accepted_at' => '0000-00-00 00:00:00','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => '8R9696','room_id' => '10014','host_id' => '10004' ,'user_id' =>'10001' ,'checkin' => '2016-05-17','checkout' =>'2016-05-20', 'number_of_guests' =>'1','nights' =>'3','per_night'=>'150','subtotal' => '450','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '23','host_fee' =>'0', 'total' =>'473','currency_code' =>'USD','transaction_id'=>'65W7164965947523P','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Accepted','type'=>'reservation','friends_email' => '','cancelled_by' => NULL,'cancelled_reason' => '' ,'decline_reason' =>'' ,'accepted_at' => '2016-05-01 20:05:31','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => 'CBID09','room_id' => '10001','host_id' => '10001' ,'user_id' =>'10003' ,'checkin' => '2016-05-16','checkout' =>'2016-05-20', 'number_of_guests' =>'1','nights' =>'4','per_night'=>'150','subtotal' => '600','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '30','host_fee' =>'0', 'total' =>'630','currency_code' =>'USD','transaction_id'=>'96C81688DH5303406','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Cancelled','type'=>'reservation','friends_email' => '','cancelled_by' => 'Host','cancelled_reason' => 'no_longer_available' ,'decline_reason' =>'' ,'accepted_at' => '2016-05-02 18:05:14','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'2016-05-02 18:05:03','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

        	['code' => '5EZ4FY','room_id' => '10015','host_id' => '10001' ,'user_id' =>'10002' ,'checkin' => '2016-06-14','checkout' =>'2016-06-21', 'number_of_guests' =>'1','nights' =>'7','per_night'=>'77','subtotal' => '539','cleaning' => '0','additional_guest' => '0' ,'security' =>'0' ,'service' => '27','host_fee' =>'0', 'total' =>'566','currency_code' =>'USD','transaction_id'=>'8U3003433U049530P','paymode' => 'PayPal','cancellation' => 'Flexible' ,'first_name' =>'' ,'last_name' => '','postal_code' =>'', 'country' =>'AF','status' =>'Accepted','type'=>'reservation','friends_email' => '','cancelled_by' => 'Host','cancelled_reason' => NULL,'decline_reason' =>'' ,'accepted_at' => '2016-05-02 18:05:07','expired_at' =>'0000-00-00 00:00:00', 'declined_at' =>'0000-00-00 00:00:00','cancelled_at' =>'0000-00-00 00:00:00','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53']

            ]);


    }
}
