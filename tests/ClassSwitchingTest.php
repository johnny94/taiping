<?php

use App\User;
use App\Role;
use App\Leave;
use App\ClassSwitching;

class ClassSwitchingTest extends TestCase {

	public function fetchUser()
	{		
		return User::find(1);
	}

	public function fetchManager()
	{	
		$ROLE_MANAGER = 2;
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


	public function testListAllUserWithLeaves()
	{
		Session::start();
		$user = $this->fetchManager();
		$this->be($user);

		$leave = new Leave;
		$leave->user_id = $user->id;
		$leave->type_id = 1;
		$leave->from = Carbon\Carbon::now();
		$leave->to = Carbon\Carbon::now();
		$leave->curriculum_id = 1;
		$leave->save();

		$this->call('POST', 'leaves/all',
			['_token'=> csrf_token()]);
		$this->assertResponseOk();
		$this->assertEquals(1, Leave::all()->count());
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
		$this->assertRedirectedToAction('ClassSwitchingsController@create');
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
		$this->assertRedirectedTo('substitutes/create');
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

		$this->call('GET', "classSwitchings/{$id}");
		$this->assertResponseOk();
	}

	public function testCreateSwitchingPageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'classSwitchings/create');
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

		$response = $this->call('POST', 'classSwitchings', 
			['_token' => csrf_token(), 'classSwitching' => $classSwitchingInput]);
		//dd($response);
		
		$this->assertRedirectedTo('classes');

		return ClassSwitching::first();
	}

	/**
	* @depends testStoreClassSwitchingAfterLogin
	*/
	public function testEditClassSwitchingAfterLogin(ClassSwitching $switching)
	{
		$user = $this->fetchUser();
		$this->be($user);

		$s = ClassSwitching::create($switching->toArray());
		$id = $s->id;

		$this->call('GET', "classSwitchings/{$id}/edit");
		$this->assertResponseOk();

		return $s;
	}

	/**
	* @depends testEditClassSwitchingAfterLogin
	*/
	public function testUpdateClassSwitchingAfterLogin(ClassSwitching $switching)
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);

		$s = ClassSwitching::create($switching->toArray());
		$id = $s->id;

		$classSwitchingInput = array([			
			'teacher' => 2,
			'from_date' => Carbon\Carbon::now()->toDateString(),
			'from_period' => 1,
			'from_class' => 1,
			'to_date' => Carbon\Carbon::now()->toDateString(),
			'to_period' => 1,
			'to_class' => 1,
			'checked_status' => 1
		]);

		$response = $this->call('PATCH', "classSwitchings/{$id}", 
			['_token' => csrf_token(), 'classSwitching' => $classSwitchingInput]);		
		
		$this->assertRedirectedTo('classes');
		$this->assertEquals(2, ClassSwitching::first()->with_user_id);

	}

	/**
	* @depends testStoreClassSwitchingAfterLogin
	*/
	public function testPassClassSwitchingAfterLogin(ClassSwitching $switching)
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);
		$PASS = 2;

		$s = ClassSwitching::create($switching->toArray());
		$id = $s->id;

		$response = $this->call('PATCH', "classSwitchings/{$id}/pass", ['_token' => csrf_token()]);
		$this->assertRedirectedTo('classes');
		$this->assertEquals($PASS, ClassSwitching::first()->checked_status_id);
	}

	/**
	* @depends testStoreClassSwitchingAfterLogin
	*/
	public function testRejectClassSwitchingAfterLogin(ClassSwitching $switching)
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);
		$REJECT = 3;

		$s = ClassSwitching::create($switching->toArray());
		$id = $s->id;

		$this->call('PATCH', "classSwitchings/{$id}/reject",
			['_token' => csrf_token()]);
		$this->assertRedirectedTo('classes');
		$this->assertEquals($REJECT, ClassSwitching::first()->checked_status_id);
	}


	public function testNotCheckedSwitchingPageAfterLogin()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$this->call('GET', 'classSwitchings/notChecked');
		$this->assertResponseOk();
		$this->assertViewHas(['pendingSwitchings','rejectedSwitchings','pendingSwitchingsFromOthers']);
	}

	public function testGetTeacherNames()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$query = ['q' => 'query text'];
		$response = $this->call('GET', 'teachers', $query); 		
		$this->assertResponseOk();
		$this->assertInternalType('array', json_decode($response->getContent()));
	}

}
