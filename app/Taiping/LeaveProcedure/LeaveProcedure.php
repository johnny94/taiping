<?php namespace App\Taiping\LeaveProcedure;

use \Auth;
use Session;

use Carbon\Carbon;
use App\Leave;

abstract class LeaveProcedure {

	public function applyProcedure()
	{
		$leave = $this->applyLeave();		
		$this->handleCurriculum($leave);			
	}

	protected function applyLeave()
	{
		//TODO: prevent from creating switching without creating leave first
		$leaveFromRequest = Session::get('leave', []);
		$from_date =  $leaveFromRequest['from_date'];
		$from_time =  $leaveFromRequest['from_time'];
		$to_date =  $leaveFromRequest['to_date'];
		$to_time =  $leaveFromRequest['to_time'];

		$leave = new Leave;
		$leave->from = Carbon::createFromFormat('Y-m-d H:i', "$from_date $from_time");
		$leave->to = Carbon::createFromFormat('Y-m-d H:i', "$to_date $to_time");
		$leave->reason = $leaveFromRequest['reason'];
		$leave->type_id = $leaveFromRequest['leaveType'];
		$leave->curriculum_id = $leaveFromRequest['curriculum'];

		Auth::user()->leaves()->save($leave);

		return $leave;
	}

	abstract protected function handleCurriculum($leave);	

}