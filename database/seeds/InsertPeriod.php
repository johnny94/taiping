<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InsertPeriod extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = Carbon\Carbon::now();
		for ($i = 1; $i <= 7 ; $i++) { 
			DB::table('periods')->insert([
				'name' => "第 {$i} 節",
				'created_at' => $date,
				'updated_at' => $date
			]);			
		}
	}

}
