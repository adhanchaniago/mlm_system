<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatestripePaymentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('payment_id');
            $table->text('user_id');
            $table->text('card_number');
            $table->text('amount');
            $table->text('date');
            $table->text('name');
            $table->text('email');
            $table->text('phone');
            $table->text('address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stripe_payments');
    }
}
