<?php
use Carbon\Carbon;

use App\User;
use App\Leave;
use App\Substitute;


class SubstituteTest extends TestCase {

	public function fetchUser()
	{	
		return User::find(1);
	}

	public function testSubstitutePageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);
		$HALF_DAY = 1;

		$leave = new Leave;
		$leave->user_id = $user->id;
		$leave->type_id = 1;
		$leave->from = Carbon::now();
		$leave->to = Carbon::now();
		$leave->curriculum_id = 3;
		$leave->save();

		$substitute = new Substitute;
		$substitute->user_id = $user->id;
		$substitute->leave_id = $leave->id;
		$substitute->substitute_teacher = 'Teacher\'s Name';
		$substitute->duration_type = $HALF_DAY;
		$substitute->am_pm = 'am';
		$substitute->from = Carbon::now();
		$substitute->to = Carbon::now();
		$substitute->save();
		$id = $substitute->id;

		$this->call('GET', "substitutes/{$id}");
		$this->assertResponseOk();
	}

	public function testCreateSubstitutePageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'leaves/substitute/create');
		$this->assertResponseOk();
		$this->assertViewHas('periods');
	}

	public function testStoreASubstituteAfterLogin()
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);

		$leaveInput = [
			'_token' => csrf_token(),
			'leaveType' => '1',
			'from_date' => Carbon::now()->toDateString(),
			'from_time' => Carbon::now()->format('H:i'),
			'to_date' => Carbon::now()->toDateString(),
			'to_time' => Carbon::now()->format('H:i'),
			'curriculum' => '1'
		];		
		Session::put('leave', $leaveInput);

		$substituteInput = [
			'_token' => csrf_token(),
			'substitute_teacher' => 'Teacher Name',
			'duration_type' => 'half_day',
			'half_day' => [
				'date' => Carbon::now()->toDateString(),
				'am_pm' => 'am'
			],
			'full_day' => [
				'from_date' => Carbon::now()->toDateString(),
				'to_date' => Carbon::now()->toDateString()
			],
			'period' => [
				'date' => Carbon::now()->toDateString()
			]
		];

		$this->call('POST', 'leaves/substitutes', $substituteInput);
		$this->assertRedirectedTo('classes');

	}

}