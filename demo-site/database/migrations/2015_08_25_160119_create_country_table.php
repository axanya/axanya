<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('country');

        Schema::create('country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_name',5)->unique();
            $table->string('long_name',100);
            $table->string('iso3',10);
            $table->string('num_code',10);
            $table->string('phone_code',10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('country');
    }
}
