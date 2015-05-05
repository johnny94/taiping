<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('periods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});

		Schema::create('period_substitute', function(Blueprint $table)
		{
			$table->integer('period_id')->unsigned();
			$table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

			$table->integer('substitute_id')->unsigned();
			$table->foreign('substitute_id')->references('id')->on('substitutes')->onDelete('cascade');

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
		Schema::drop('periods');
		Schema::drop('period_substitute');
	}

}
