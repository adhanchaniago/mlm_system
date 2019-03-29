<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatesalescontentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salescontents', function (Blueprint $table) {
            $table->increments('id');
            $table->text('company_id');
            $table->text('type');
            $table->text('content');
            $table->text('image');
            $table->text('title');
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
        Schema::drop('salescontents');
    }
}
