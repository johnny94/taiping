<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CheckedStatusSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = Carbon\Carbon::now();
		DB::table('checked_status')->insert([
			'title' => 'pending',
			'created_at' => $date,
			'updated_at' => $date
			]);

		DB::table('checked_status')->insert([
			'title' => 'pass',
			'created_at' => $date,
			'updated_at' => $date
			]);

		DB::table('checked_status')->insert([
			'title' => 'reject',
			'created_at' => $date,
			'updated_at' => $date
			]);		
	}

}
