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
			'補假',
			'公假',
			'休假',
			'病假',
			'事假',
			'婚假',
			'喪假',
			'分娩假',
			'產前假',
			'陪產假',
			'流產假',
			'生理假',
			'家庭照顧假',
			'捐贈器官骨髓假'
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
