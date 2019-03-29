<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateemailcontentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emailcontents', function (Blueprint $table) {
            $table->increments('id');
            $table->text('company_id');
            $table->text('smtp');
            $table->text('smtp_user_id');
            $table->text('smtp_password');
            $table->text('welcome_text');
            $table->text('new_password_text');
            $table->text('new_affiliate_text');
            $table->text('delete_account_text');
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
        Schema::drop('emailcontents');
    }
}
