<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class Curriculums extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = Carbon\Carbon::now();
		$curriculums = [
			'無課務',
			'調課',
			'代課老師'
		];

		foreach ($curriculums as $curriculum) {
			DB::table('classTitles')->insert([
				'title' => $curriculum,
				'created_at' => $date,
				'updated_at' => $date
			]);
		}		
	}
}
