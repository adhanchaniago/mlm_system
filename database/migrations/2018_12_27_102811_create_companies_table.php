<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatecompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('address');
            $table->text('email');
            $table->text('phno');
            $table->text('bill_address');
            $table->text('card_stripe');
            $table->text('logo');
            $table->text('planid');
            $table->text('domain_name');
            $table->text('folder');
            $table->text('activated');
            $table->text('valid');
            $table->text('status');
            $table->text('apikey');
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
        Schema::drop('companies');
    }
}
