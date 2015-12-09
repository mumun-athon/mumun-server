<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaidLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raid_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('raid_id')->unsigned();
            $table->string('longitude');
            $table->string('latitude');
            $table->timestamps();

	    $table->foreign('raid_id')
		    ->references('id')
		    ->on('raids')
		    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('raid_locations');
    }
}
