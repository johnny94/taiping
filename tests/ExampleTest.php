<?php

use App\User;
use App\Role;
use App\Leave;
use App\ClassSwitching;

class ExampleTest extends TestCase {
	
	public function userManagerProvider()
	{
		$user = new User(['name'=>'Johnny', 
			'email'=>'johnny@example.com']);
		$user->id = 1;

		return array(array($user));
	}

	public function userProvider()
	{
		$user = new User(['name'=>'Johnny', 
			'email'=>'johnny@example.com']);		

		return array(array($user));
	}

	public function fetchUser()
	{		
		return User::find(1);
	}

	public function fetchManager()
	{	$ROLE_MANAGER = 2;
		$managers = Role::where('id', '=', $ROLE_MANAGER)
					->first()
					->users;

		return $managers->first();
	}
	
	public function testHomePageAfterLogin()
	{		
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', '/');
		$this->assertResponseOk();
	}

	public function testClassesPageAfterLogin()
	{		
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'classes');
		$this->assertResponseOk();
	}	
	
	public function testCreateLeavePageAfterLogin()
	{		
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'leaves/create');
		$this->assertResponseOk();
	}
	
	public function testLeavesPageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);
		$this->call('GET', 'leaves');
		$this->assertRedirectedTo('classes');

		$user = $this->fetchManager();
		$this->be($user);
		$this->call('GET', 'leaves');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testListAllUserWithLeaves($user)
	{		
		$this->be($user);

		$this->call('POST', 'leaves/all');
		$this->assertResponseOk();
	}

	public function testCreateLeaveWithoutCurriculum()
	{
		Session::start();		
		$user = $this->fetchUser();
		$this->be($user);

		$NO_CURRICULUM = '1';

		$input = [
			'_token' => csrf_token(),
			'leaveType' => '1',
			'from_date' => Carbon\Carbon::now()->toDateString(),
			'from_time' => Carbon\Carbon::now()->format('H:i'),
			'to_date' => Carbon\Carbon::now()->toDateString(),
			'to_time' => Carbon\Carbon::now()->format('H:i'),
			'curriculum' => $NO_CURRICULUM
		];

		$response = $this->call('POST', 'leaves', $input);		
		$this->assertRedirectedTo('classes');
		$this->assertSessionHas('leave');
	}

	public function testCreateLeaveWithSwitchingClass()
	{
		Session::start();		
		$user = $this->fetchUser();
		$this->be($user);

		$SWITCHING_ID = '2';

		$this->call('POST', 'leaves', 
			['_token' => csrf_token(), 'curriculum' => $SWITCHING_ID]);
		$this->assertRedirectedTo('leaves/switching/create');
		$this->assertSessionHas('leave');
	}

	public function testCreateLeaveWithSubstitute()
	{
		Session::start();		
		$user = $this->fetchUser();
		$this->be($user);

		$SUBSTITUTE_ID = '3';
		
		$this->call('POST', 'leaves', 
			['_token' => csrf_token(), 'curriculum' => $SUBSTITUTE_ID]);
		$this->assertRedirectedTo('leaves/substitute/create');
		$this->assertSessionHas('leave');
	}

	public function createLeave($userId)
	{
		$leave = new Leave;
		$leave->user_id = $userId;
		$leave->from = Carbon\Carbon::now()->format('Y-m-d H:i');
		$leave->to = Carbon\Carbon::now()->format('Y-m-d H:i');
		$leave->type_id = 1;
		$leave->curriculum_id = 1;
		$leave->save();

		return $leave;
	}

	public function createClassSwitching($user_id, $leave_id)
	{
		$class_switching = new ClassSwitching;
		$class_switching->user_id = $user_id;
		$class_switching->leave_id = $leave_id;
		$class_switching->with_user_id = 2;
		$class_switching->from = Carbon\Carbon::now()->format('Y-m-d');
		$class_switching->from_period = 1;
		$class_switching->from_class_id = 1;
		$class_switching->to = Carbon\Carbon::now()->format('Y-m-d');
		$class_switching->to_period = 1;
		$class_switching->to_class_id = 1;
		$class_switching->checked_status_id = 1;
		$class_switching->save();

		return $class_switching;
	}

	public function testClassSwitchingPageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$leave = $this->createLeave($user->id);
		$class_switching = $this->createClassSwitching($user->id, $leave->id);
		$id = $class_switching->id;

		$this->call('GET', "switchings/{$id}");
		$this->assertResponseOk();
	}

	public function testCreateSwitchingPageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'leaves/switching/create');
		$this->assertResponseOk();
	}


	public function testStoreClassSwitchingAfterLogin()
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);		

		$leaveInput = [
			'_token' => csrf_token(),
			'leaveType' => '1',
			'from_date' => Carbon\Carbon::now()->toDateString(),
			'from_time' => Carbon\Carbon::now()->format('H:i'),
			'to_date' => Carbon\Carbon::now()->toDateString(),
			'to_time' => Carbon\Carbon::now()->format('H:i'),
			'curriculum' => '1'
		];		
		Session::put('leave', $leaveInput);

		$classSwitchingInput = array([			
			'teacher' => 1,
			'from_date' => Carbon\Carbon::now()->toDateString(),
			'from_period' => 1,
			'from_class' => 1,
			'to_date' => Carbon\Carbon::now()->toDateString(),
			'to_period' => 1,
			'to_class' => 1,
			'checked_status' => 1
		]);

		$response = $this->call('POST', 'leaves/switchings', 
			['_token' => csrf_token(), 'classSwitching' => $classSwitchingInput]);		
		$this->assertRedirectedTo('classes');
	}

	/**
	* @dataProvider userProvider
	*/
	public function testEditClassSwitchingAfterLogin($user)
	{
		$this->be($user);

		$this->call('GET', 'switchings/1/edit');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testUpdateClassSwitchingAfterLogin($user)
	{
		$this->be($user);

		$this->call('POST', 'switchings/1');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testPassClassSwitchingAfterLogin($user)
	{
		$this->be($user);

		$this->call('POST', 'switchings/1/pass');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testRejectClassSwitchingAfterLogin($user)
	{
		$this->be($user);

		$this->call('POST', 'switchings/1/reject');
		$this->assertResponseOk();
	}

}
