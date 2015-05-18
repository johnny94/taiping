<?php
use Carbon\Carbon;

use App\User;
use App\Leave;
use App\Substitute;

use App\Taiping\LeaveApplication\NoCurriculum;


class SubstituteTest extends TestCase {

	public function fetchUser()
	{	
		return User::find(1);
	}

	// TODO: Need To move to other place (ex. Helper class).
	public function createLeave($userId)
	{
		$leave = new Leave;
		$leave->user_id = $userId;
		$leave->from = Carbon::now()->format('Y-m-d H:i');
		$leave->to = Carbon::now()->format('Y-m-d H:i');
		$leave->type_id = 1;
		$leave->curriculum_id = 1;
		$leave->reason = 'Reason';
		$leave->save();

		return $leave;
	}

	// TODO: Need To move to other place (ex. Helper class).
	public function getCreateLeaveInput($type, $curriculum, $reason = 'Reason')
	{
		return [
			'_token' => csrf_token(),
			'leaveType' => $type,
			'from_date' => Carbon::now()->toDateString(),
			'from_time' => Carbon::now()->format('H:i'),
			'to_date' => Carbon::now()->toDateString(),
			'to_time' => Carbon::now()->format('H:i'),
			'reason' => $reason,
			'curriculum' => $curriculum
		];
	}


	public function testSubstitutePageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);
		$HALF_DAY = 1;

		$leave = $this->createLeave($user->id);

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

		$this->call('GET', 'substitutes/create');
		$this->assertResponseOk();
		$this->assertViewHas('periods');
	}

	public function testStoreASubstituteAfterLogin()
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);

		$leaveInput = $this->getCreateLeaveInput('1', '1');

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
				'date' => Carbon::now()->toDateString(),
				'periods' => ['1']
			]
		];

		$response = $this->call('POST', 'substitutes', $substituteInput);		
		$this->assertRedirectedTo('classes');

	}

}