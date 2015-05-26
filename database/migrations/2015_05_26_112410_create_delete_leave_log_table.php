<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteLeaveLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delete_leave_log', function(Blueprint $table)
		{
			$table->increments('id');			
			$table->integer('manager_id')->unsigned();
			$table->integer('leave_id')->unsigned();
			$table->timestamps();

			$table->foreign('manager_id')
			      ->references('id')
			      ->on('users')
			      ->onDelete('cascade');

			$table->foreign('leave_id')
			      ->references('id')
			      ->on('leaves')
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
		Schema::drop('deleteLeaveLog');
	}

}
