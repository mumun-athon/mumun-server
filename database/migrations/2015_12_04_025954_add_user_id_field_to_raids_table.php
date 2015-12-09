<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdFieldToRaidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raids', function (Blueprint $table) {
          $table->integer('user_id')->unsigned();

	  $table->foreign('user_id')
		  ->references('id')
		  ->on('users')
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
        Schema::table('raids', function (Blueprint $table) {
            $table->dropForeign('raids_user_id_foreign');
        });
    }
}