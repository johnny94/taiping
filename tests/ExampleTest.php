<?php

use App\User;
use App\Role;

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

	public function testCreateLeaveStep1()
	{
		$this->call('POST', 'leaves');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testClassSwitchingPageAfterLogin($user)
	{
		$this->be($user);

		$this->call('GET', 'switchings/1');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testCreateSwitchingPageAfterLogin($user)
	{
		$this->be($user);

		$this->call('GET', 'leaves/switching/create');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testStoreClassSwitchingAfterLogin($user)
	{
		$this->be($user);

		$this->call('POST', 'leaves/switchings');
		$this->assertResponseOk();
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
