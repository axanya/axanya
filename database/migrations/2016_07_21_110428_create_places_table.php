<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('city',100);
            $table->string('state',100);
            $table->string('country',5);
            $table->foreign('country')->references('short_name')->on('country');
            $table->string('postal_code',25);
            $table->string('latitude',50);
            $table->string('longitude',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('places');
    }
}
