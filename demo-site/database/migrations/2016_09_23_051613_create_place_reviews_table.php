<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('place_reviews');

        Schema::create('place_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_from')->unsigned();
            $table->integer('place_id');
            $table->integer('place');
            $table->text('place_comments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('place_reviews', function (Blueprint $table) {
            Schema::drop('place_reviews');
        });
    }
}
