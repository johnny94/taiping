<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSwitchingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('class_switchings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('leave_id')->unsighed();
			$table->integer('user_id')->unsigned();			
			$table->integer('with_user_id')->unsighed();
			$table->date('from');
			$table->integer('from_period')->unsighed();
			$table->integer('from_class_id')->unsighed();
			$table->date('to');
			$table->integer('to_period')->unsighed();
			$table->integer('to_class_id')->unsighed();
			$table->integer('checked_status_id')->unsighed();
			$table->timestamps();

			$table->foreign('leave_id')
			      ->references('id')
			      ->on('leaves')
			      ->onDelete('cascade');

			$table->foreign('user_id')
			      ->references('id')
			      ->on('users')
			      ->onDelete('cascade');

			$table->foreign('with_user_id')
			      ->references('id')
			      ->on('users')
			      ->onDelete('cascade');

			$table->foreign('from_class_id')
			      ->references('id')
			      ->on('classTitle')
			      ->onDelete('cascade');

			$table->foreign('to_class_id')
			      ->references('id')
			      ->on('classTitle')
			      ->onDelete('cascade');

			$table->foreign('checked_status_id')
			      ->references('id')
			      ->on('checked_status')
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
		Schema::drop('class_switchings');
	}

}
