<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatefrontPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('slider_image');
            $table->text('slider_text');
            $table->text('aboutUs_main_description');
            $table->text('aboutUs_sub_description');
            $table->text('aboutUs_image');
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
        Schema::drop('front_pages');
    }
}
