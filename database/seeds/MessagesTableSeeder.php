<?php

use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->delete();

        DB::table('messages')->insert([

        		['room_id' => '10007', 'reservation_id' => '10001','user_to' => '10001' ,'user_from' =>'10002' ,'message' => 'Hi test, your room is looking good.. ','message_type' =>'1', 'read' =>'1','archive' =>'1','star'=>'1','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        		['room_id' => '10007', 'reservation_id' => '10001','user_to' => '10002' ,'user_from' =>'10001' ,'message' => 'Hi, John...Thanks for booking my room.. :)','message_type' =>'2', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],
        		['room_id' => '10005', 'reservation_id' => '10002','user_to' => '10001' ,'user_from' =>'10003' ,'message' => 'Hi, this is first time i come to London... may i know the map details?','message_type' =>'1', 'read' =>'0','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
        		['room_id' => '10001', 'reservation_id' => '10003','user_to' => '10001' ,'user_from' =>'10004' ,'message' => 'Hi, your room is good..','message_type' =>'2', 'read' =>'1','archive' =>'0','star'=>'1','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
        		['room_id' => '10005', 'reservation_id' => '10004','user_to' => '10001' ,'user_from' =>'10002' ,'message' => 'Hi Host,i am john..please give some offer for these days... :)','message_type' =>'9', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:24:53'],
				['room_id' => '10005', 'reservation_id' => '10004','user_to' => '10002' ,'user_from' =>'10001' ,'message' => 'Hi, john good to see your message..','message_type' =>'5', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
				['room_id' => '10014', 'reservation_id' => '10005','user_to' => '10004' ,'user_from' =>'10001' ,'message' => 'Hi Mick, your list is good... ','message_type' =>'1', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
				['room_id' => '10014', 'reservation_id' => '10005','user_to' => '10001' ,'user_from' =>'10004' ,'message' => 'Thanks for Booking...we are waiting for you... :)','message_type' =>'2', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
				['room_id' => '10001', 'reservation_id' => '10006','user_to' => '10001' ,'user_from' =>'10003' ,'message' => 'Hi Host,...I am john','message_type' =>'1', 'read' =>'1','archive' =>'1','star'=>'1','special_offer_id' => NULL,'created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:14:53'],
				['room_id' => '10001', 'reservation_id' => '10006','user_to' => '10001' ,'user_from' =>'10001' ,'message' => 'Hi Tony.. thanks for booking','message_type' =>'2', 'read' =>'1','archive' =>'1','star'=>'1','special_offer_id' => NULL,'created_at' => '2016-05-02 19:31:48','updated_at' => '2016-05-02 20:14:53'],
				['room_id' => '10001', 'reservation_id' => '10006','user_to' => '10001' ,'user_from' =>'10003' ,'message' => 'Sorry, my place is in longer available.....','message_type' =>'11', 'read' =>'1','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-02 19:31:48','updated_at' => '2016-05-02 20:14:53'],
				['room_id' => '10015', 'reservation_id' => '10007','user_to' => '10001' ,'user_from' =>'10002' ,'message' => 'Hi Host, I am John, I am coming from US... :)','message_type' =>'1', 'read' =>'1','archive' =>'0','star'=>'1','special_offer_id' => NULL,'created_at' => '2016-05-02 19:31:48','updated_at' => '2016-05-02 20:14:53'],
				['room_id' => '10015', 'reservation_id' => '10007','user_to' => '10002' ,'user_from' =>'10001' ,'message' => 'HI john, thanks for booking..','message_type' =>'2', 'read' =>'0','archive' =>'0','star'=>'0','special_offer_id' => NULL,'created_at' => '2016-05-02 19:31:48','updated_at' => '2016-05-02 20:14:53'],

        		
        	]);
    }
}
