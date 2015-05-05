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
		Schema::create('leaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('type_id')->unsighed();
			$table->timestamp('from');
			$table->timestamp('to');
			$table->integer('curriculum_id')->unsighed();		
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
	}

}
