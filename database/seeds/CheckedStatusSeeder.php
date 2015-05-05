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
		DB::insert('insert into checked_status (title) values (?)', ['pending']);
		DB::insert('insert into checked_status (title) values (?)', ['pass']);
		DB::insert('insert into checked_status (title) values (?)', ['reject']);
		
	}

}
