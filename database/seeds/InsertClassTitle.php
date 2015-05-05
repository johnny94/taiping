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
		DB::insert('insert into classTitles (title) values (?)', ['國語']);
		DB::insert('insert into classTitles (title) values (?)', ['英語']);
		DB::insert('insert into classTitles (title) values (?)', ['本土語']);
		DB::insert('insert into classTitles (title) values (?)', ['藝文(音樂)']);
		DB::insert('insert into classTitles (title) values (?)', ['藝文(美勞)']);
		DB::insert('insert into classTitles (title) values (?)', ['數學']);
		DB::insert('insert into classTitles (title) values (?)', ['社會']);
		DB::insert('insert into classTitles (title) values (?)', ['自然與生活科技']);
		DB::insert('insert into classTitles (title) values (?)', ['生活(音樂)']);
		DB::insert('insert into classTitles (title) values (?)', ['生活(美勞)']);
		DB::insert('insert into classTitles (title) values (?)', ['生活(導師)']);
		DB::insert('insert into classTitles (title) values (?)', ['綜合']);
		DB::insert('insert into classTitles (title) values (?)', ['體育']);
		DB::insert('insert into classTitles (title) values (?)', ['健康']);
		DB::insert('insert into classTitles (title) values (?)', ['電腦']);
		DB::insert('insert into classTitles (title) values (?)', ['彈性']);	
	}

}
