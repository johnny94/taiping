<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Role;

class InsertTestAccount extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = Carbon\Carbon::now();

		$user = User::create([
			'name' => 'user',
			'email' => 'user@example.com',
			'active' => true,
			'password' => bcrypt('password')
		]);

		DB::table('role_user')->insert([			
			'role_id' => Role::USER,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);
		
		$user = User::create([
			'name' => 'manager',
			'email' => 'manager@example.com',
			'active' => true,
			'password' => bcrypt('password')
		]);

		DB::table('role_user')->insert([
			'role_id' => Role::USER,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);

		DB::table('role_user')->insert([			
			'role_id' => Role::ADMIN,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);	
		
	}
}
