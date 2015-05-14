<?php namespace App\Taiping\LeaveProcedure;

use \Auth;
use DB;
use Request;

use Carbon\Carbon;

class ClassSwitchingProcedure extends LeaveProcedure {

	protected function handleCurriculum($leave)
	{
		$classSwitchings = Request::input('classSwitching');
		foreach ($classSwitchings as $switching) {
			$class_switching = new \App\ClassSwitching;
			$class_switching->leave_id = $leave->id;
			$class_switching->with_user_id = $switching['teacher'];
			$class_switching->from = Carbon::createFromFormat('Y-m-d', $switching['from_date']);
			$class_switching->from_period = intval($switching['from_period']);
			$class_switching->from_class_id = intval($switching['from_class']);
			$class_switching->to = Carbon::createFromFormat('Y-m-d', $switching['to_date']);
			$class_switching->to_period = intval($switching['to_period']);
			$class_switching->to_class_id = intval($switching['to_class']);
			$class_switching->checked_status_id = DB::table('checked_status')->where('title', 'pending')->first()->id;
			Auth::user()->classSwitching()->save($class_switching);			
		}
	}
}