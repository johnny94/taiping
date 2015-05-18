<?php

use App\Leave;

class LeaveTest extends TestCase {

	public function testNoCurriculumConstant()
	{
		$this->assertEquals(Leave::NO_CURRICULUM, '1');
	}

	public function testClassSwitchingConstant()
	{
		$this->assertEquals(Leave::CLASS_SWITCHING, '2');
	}

	public function testSubstituteConstant()
	{
		$this->assertEquals(Leave::SUBSTITUTE, '3');
	}

}
