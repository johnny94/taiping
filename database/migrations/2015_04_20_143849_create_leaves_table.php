<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leavetypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->timestamps();
		});

		Schema::create('curriculums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->timestamps();
		});

		Schema::create('leaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('type_id')->unsigned();
			$table->timestamp('from');
			$table->timestamp('to');
			$table->text('reason');
			$table->integer('curriculum_id')->unsigned();		
			$table->timestamps();

			$table->foreign('user_id')
			      ->references('id')
			      ->on('users')
			      ->onDelete('cascade');

			$table->foreign('type_id')
			      ->references('id')
			      ->on('leavetypes')
			      ->onDelete('cascade');

			$table->foreign('curriculum_id')
			      ->references('id')
			      ->on('curriculums')
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
		Schema::drop('leaves');
		Schema::drop('curriculums');
		Schema::drop('leavetypes');
	}

}
