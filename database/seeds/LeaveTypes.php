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
		DB::insert('insert into leavetypes (title) values (?)', ['事假']);
		DB::insert('insert into leavetypes (title) values (?)', ['公假']);
		DB::insert('insert into leavetypes (title) values (?)', ['婚嫁']);
		DB::insert('insert into leavetypes (title) values (?)', ['喪假']);
		DB::insert('insert into leavetypes (title) values (?)', ['產假']);		
	}
}
