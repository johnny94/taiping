<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;

class InsertTestAccount extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$ROLE_USER = 1;
		$ROLE_MANAGER = 2;
		$date = Carbon\Carbon::now();


		$user = User::create([
			'name' => 'user',
			'email' => 'test@example.com',
			'password' => bcrypt('password')
		]);

		DB::table('role_user')->insert([			
			'role_id' => $ROLE_USER,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);
		
		$user = User::create([
			'name' => 'manager',
			'email' => 'manager@example.com',
			'password' => bcrypt('password')
		]);

		DB::table('role_user')->insert([			
			'role_id' => $ROLE_USER,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);

		DB::table('role_user')->insert([			
			'role_id' => $ROLE_MANAGER,
			'user_id' => $user->id,
			'created_at' => $date,
			'updated_at' => $date
			]);	
		
	}
}
