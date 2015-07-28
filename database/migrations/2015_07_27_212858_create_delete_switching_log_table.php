<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteSwitchingLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delete_switching_log', function(Blueprint $table)
		{
			$table->increments('id');			
			$table->integer('manager_id')->unsigned();
			$table->integer('switching_id')->unsigned();
			$table->timestamps();

			$table->foreign('manager_id')
			      ->references('id')
			      ->on('users')
			      ->onDelete('cascade');

			$table->foreign('switching_id')
			      ->references('id')
			      ->on('class_switchings')
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
		Schema::drop('delete_switching_log');
	}

}
