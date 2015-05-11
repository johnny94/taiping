<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LeaveTypes extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = Carbon\Carbon::now();
		$leaveTypes = [
			'事假',
			'公假',
			'婚嫁',
			'喪假',
			'產假'
		];

		foreach ($leaveTypes as $type) {
			DB::table('leavetypes')->insert([
				'title' => $type,
				'created_at' => $date,
				'updated_at' => $date
			]);
		}	
	}
}
