<?php

use Illuminate\Database\Seeder;

class PaymentGatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateway')->delete();

        DB::table('payment_gateway')->insert([
        		['name' => 'username', 'value' => 'pvignesh90-facilitator_api1.gmail.com', 'site' => 'PayPal'],
        		['name' => 'password', 'value' => '1381798304', 'site' => 'PayPal'],
        		['name' => 'signature', 'value' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacALfsdnEmmarK-6V7JsbXFL2.hoZ8', 'site' => 'PayPal'],
        		['name' => 'mode', 'value' => 'sandbox', 'site' => 'PayPal']
        	]);
    }
}
