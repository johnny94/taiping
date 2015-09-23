<?php

use \Mockery;

use App\User;
use App\ClassSwitching;

class ClassSwitchingTest extends TestCase
{	

	public function testVistsHomePageAfterLogin()
	{
		$user = new User(['name' => 'John']);
		$this->be($user);		

		$response = $this->call('GET', '/');
		$this->assertViewHas('passedSwitchingsFromOthers');
		$this->assertViewHas('passedSwitchings');
		$this->assertResponseOk();
	}

	//Can't pass. I have no idea.
	public function testVisitCreateClassSwitchingPage()
	{
		$user = new User(['id' => 1, 'name' => 'John']);
		$this->be($user);

		$sw = Mockery::mock('Eloquent', 'App\ClassSwitching');
		$sw->shouldReceive('getAttribute')->withAnyArgs()->andReturn($sw);
		$this->app->instance('App\ClassSwitching', $sw);
		
		$response = $this->call('GET', 'class-switchings/create');
		$this->assertViewHas('periods');
		$this->assertViewHas('classes');
		$this->assertResponseOk();
	}

	public function testShowClassSwitching()
	{
		$user = new User(['name' => 'John']);
		$this->be($user);

		$sw = Mockery::mock('App\ClassSwitching');
		$sw->shouldReceive('getAttribute')->withAnyArgs()->andReturn($sw);
		
		$mock = Mockery::mock('App\Taiping\Repositories\ClassSwitchingRepository');		
		$mock->shouldReceive('findOrFail')->with('1')->andReturn($sw);
		$this->app->instance('App\Taiping\Repositories\ClassSwitchingRepository', $mock);

		$response = $this->call('GET', 'classSwitchings/1');
		$this->assertViewHas('switching');		
		$this->assertResponseOk();
	}


	/*public function testHomePageAfterLogin()
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

	public function testDestroyClassSwitching()
	{
		Session::start();
		$user = $this->fetchUser();
		$this->be($user);

		$leave = $this->createLeave($user->id);
		$class_switching = $this->createClassSwitching($user->id, $leave->id);
		$id = $class_switching->id;
		$this->call('DELETE', "classSwitchings/{$id}", ['_token' => csrf_token()]);
		$this->assertRedirectedTo('classSwitchings/notChecked');

	}

	public function testGetTeacherNames()
	{
		$user = $this->fetchUser();
		$this->be($user);

		$query = ['q' => 'query text'];
		$response = $this->call('GET', 'teachers', $query); 		
		$this->assertResponseOk();
		$this->assertInternalType('array', json_decode($response->getContent()));
	}*/

}
