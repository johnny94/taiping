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
		Schema::create('classtitles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->timestamps();
		});

		Schema::create('checked_status', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->timestamps();
		});

		Schema::create('class_switchings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('with_user_id')->unsigned();
			$table->date('from');
			$table->integer('from_period')->unsigned();
			$table->integer('from_class_id')->unsigned();
			$table->date('to');
			$table->integer('to_period')->unsigned();
			$table->integer('to_class_id')->unsigned();
			$table->integer('checked_status_id')->unsigned();
			$table->softDeletes();
			$table->timestamps();

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
			      ->on('classtitles')
			      ->onDelete('cascade');

			$table->foreign('to_class_id')
			      ->references('id')
			      ->on('classtitles')
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
		Schema::drop('classtitles');
		Schema::drop('checked_status');
	}

}
