<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InsertClassTitle extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{	
		$date = Carbon\Carbon::now();
		$classTitles = [
			'國語',
			'英語',
			'本土語',
			'藝文(音樂)',
			'藝文(美勞)',
			'藝文(美勞)',
			'數學',
			'社會',
			'自然與生活科技',
			'生活(音樂)',
			'生活(美勞)',
			'生活(導師)',
			'綜合',
			'體育',
			'健康',
			'電腦',
			'彈性'
		];

		foreach ($classTitles as $title) {
			DB::table('classTitles')->insert([
				'title' => $title,
				'created_at' => $date,
				'updated_at' => $date
			]);
		}
	}

}
