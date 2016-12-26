<?php

use Illuminate\Database\Seeder;

class PayoutPreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payout_preferences')->delete();

        DB::table('payout_preferences')->insert([

        		['user_id' => '10002', 'address1' => 'CHRIS NISWANDEE','address2' => 'SMALLSYS INC' ,'city' =>'795 E DRAGRAM' ,'state' => 'TUCSON AZ 85705','postal_code' =>'85705', 'country' =>'US','payout_method' =>'PayPal','paypal_email'=>'immanicon@gmail.com','currency_code' =>'EUR','default' => 'yes','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53'],

                ['user_id' => '10001', 'address1' => 'JOHN "GULLIBLE" DOE','address2' => 'CENTER FOR FINANCIAL ASSISTANCE TO DEPOSED NIGERIAN ROYALTY' ,'city' =>'421 E DRACHMAN' ,'state' => 'TUCSON AZ 85705-7598','postal_code' =>'85705-7598', 'country' =>'US','payout_method' =>'PayPal','paypal_email'=>'pvignesh90@gmail.com','currency_code' =>'EUR','default' => 'yes','created_at' => '2016-05-01 19:31:48','updated_at' => '2016-05-01 20:04:53']


        		]);
    }
}
