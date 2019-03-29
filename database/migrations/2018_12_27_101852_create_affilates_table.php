<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateaffilatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affilates', function (Blueprint $table) {
            $table->increments('id');
            $table->text('company_id');
            $table->text('photo');
            $table->text('activated');
            $table->text('name');
            $table->text('email');
            $table->text('phone');
            $table->text('invitee');
            $table->text('paypal_email');
            $table->text('rankid');
            $table->text('current_revenue');
            $table->text('past_revid');
            $table->text('level_p1_affiliateid');
            $table->text('level_m1_affiliateid');
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
        Schema::drop('affilates');
    }
}
