<?php

use Illuminate\Database\Seeder;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fees')->delete();

        DB::table('fees')->insert([
        		['name' => 'service_fee', 'value' => 5],
        		['name' => 'host_fee', 'value' => 0],
                ['name' => 'host_penalty', 'value' => 1],
                ['name' => 'currency', 'value' => 'USD'],
                ['name' => 'before_seven_days', 'value' => 50],
                ['name' => 'after_seven_days', 'value' => 100],
                ['name' => 'cancel_limit', 'value' => 3]

        	]);
    }
}
