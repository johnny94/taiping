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
		$date = Carbon\Carbon::now();
		DB::table('roles')->insert([
			'id' => 1,
			'title' => 'user',
			'created_at' => $date,
			'updated_at' => $date
			]);

		DB::table('roles')->insert([
			'id' => 2,
			'title' => 'admin',
			'created_at' => $date,
			'updated_at' => $date
			]);		
	}
}
