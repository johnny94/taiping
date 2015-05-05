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
		for ($i = 1; $i <= 7 ; $i++) { 
			DB::insert('insert into periods (name) values (?)', ["第 {$i} 節"]);
		}
	}

}
