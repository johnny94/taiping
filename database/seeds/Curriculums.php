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
		DB::insert('insert into curriculums (title) values (?)', ['無課務']);
		DB::insert('insert into curriculums (title) values (?)', ['調課']);
		DB::insert('insert into curriculums (title) values (?)', ['代課老師']);	
	}
}
