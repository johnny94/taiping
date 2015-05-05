<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstitutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('substitutes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('leave_id')->unsigned();
			$table->string('substitute_teacher');
			$table->integer('duration_type');
			$table->string('am_pm', 2)->nullable();
			$table->date('from');
			$table->date('to');
			$table->timestamps();

			$table->foreign('user_id')
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
		Schema::drop('substitutes');
	}

}
