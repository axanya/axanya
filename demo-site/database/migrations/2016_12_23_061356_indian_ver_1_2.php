<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class IndianVer12 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //developers rolled over the update
        //no one told them how to use migrations
        //so they just changed migration for existing tables
        //I had to add these updates to separate migration
        //so we won't lose any data in DB and make this thing work

        Schema::table('users', function (Blueprint $table)
        {
            $table->string('currency_code', 10)->nullable()->after('google_id');
        });

        Schema::table('sessions', function (Blueprint $table)
        {
            $table->integer('user_id')->nullable()->after('last_activity');
        });

        Schema::table('currency', function (Blueprint $table)
        {
            $table->enum('paypal_currency', ['Yes', 'No'])->default('No')->after('default_currency');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->dropColumn('currency_code');
        });

        Schema::table('sessions', function (Blueprint $table)
        {
            $table->dropColumn('user_id');
        });

        Schema::table('currency', function (Blueprint $table)
        {
            $table->dropColumn('paypal_currency');
        });
    }
}
