<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TeacherAccount extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{			
		$file = Storage::disk('local')->get('account.txt');
		$lines = explode(PHP_EOL, $file);

		foreach ($lines as $line) {
			$name = explode(':', $line)[4];
			$account = explode(':', $line)[0] . '@example.com';

			DB::insert('insert into users (name, email, password) values (?, ?, ?)', 
				[$name, $account, bcrypt('password')]);
			
		}
		
	}


}
