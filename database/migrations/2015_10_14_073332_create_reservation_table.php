<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reservation');

        Schema::create('reservation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',10);
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->integer('host_id')->unsigned();
            $table->foreign('host_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('checkin');
            $table->date('checkout');
            $table->integer('number_of_guests');
            $table->integer('nights');
            $table->integer('per_night');
            $table->integer('subtotal');
            $table->integer('cleaning');
            $table->integer('additional_guest');
            $table->integer('security');
            $table->integer('service');
            $table->integer('host_fee');
            $table->integer('total');
            $table->string('coupon_code',50);
            $table->integer('coupon_amount');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->string('transaction_id',50);
            $table->enum('paymode', ['PayPal', 'Credit Card'])->nullable();
            $table->enum('cancellation', ['Flexible', 'Moderate', 'Strict'])->default('Flexible');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('postal_code',20);
            $table->string('country',5);
            $table->foreign('country')->references('short_name')->on('country');
            $table->enum('status', ['Pending', 'Accepted', 'Declined', 'Expired', 'Checkin', 'Checkout', 'Completed', 'Cancelled'])->nullable();
            $table->enum('type', ['contact', 'reservation'])->nullable();
            $table->text('friends_email');
            $table->enum('cancelled_by', ['Guest', 'Host'])->nullable();
            $table->string('cancelled_reason',500);
            $table->text('decline_reason');
            $table->timestamp('accepted_at');
            $table->timestamp('expired_at');
            $table->timestamp('declined_at');
            $table->timestamp('cancelled_at');
            $table->timestamps();
        });

        $statement = "ALTER TABLE reservation AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservation');
    }
}
