<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTitle extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{			
		DB::insert('insert into roles (id, title) values (?, ?)', [1, 'user']);
		DB::insert('insert into roles (id, title) values (?, ?)', [2, 'admin']);
	}
}
