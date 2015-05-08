<?php

use App\User;

class ExampleTest extends TestCase {


	public function userProvider()
	{
		$user = new User(['name'=>'Johnny', 
			'email'=>'johnny@example.com']);

		return array(array($user));
		//$this->be($user);
	}
	
	public function testAllRouteWithoutLogin()
	{
		
	}
	
	/**
	* @dataProvider userProvider
	*/
	public function testHomePageAfterLogin($user)
	{		
		$this->be($user);

		$this->call('GET', '/');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testClassesPageAfterLogin($user)
	{		
		$this->be($user);

		$this->call('GET', 'classes');
		$this->assertResponseOk();
	}
	
	/**
	* @dataProvider userProvider
	*/
	public function testCreateLeavePageAfterLogin($user)
	{		
		$this->be($user);

		$this->call('GET', 'leaves/create');
		$this->assertResponseOk();
	}

	/**
	* @dataProvider userProvider
	*/
	public function testLeavesPageAfterLogin($user)
	{		
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

}
