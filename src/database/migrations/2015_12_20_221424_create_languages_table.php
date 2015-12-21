<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->default('Pirate');
            $table->string('native', 255)->default('Piaaarate');
            $table->string('abbr', 3)->default('arr');
            $table->boolean('active')->unsigned()->default(0);
            $table->boolean('default')->unsigned()->default(0);
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
        Schema::drop('langauges');
    }
}
