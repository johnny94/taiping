<?php namespace App\Taiping\LeaveProcedure;

use \Auth;
use DB;
use Request;

use Carbon\Carbon;

use App\Substitute;

class SubstituteProcedure extends LeaveProcedure {

	protected function handleCurriculum($leave)
	{
		$input = Request::all();
		$substitute = new Substitute;		
		$substitute->leave_id = $leave->id;
		$substitute->substitute_teacher = $input['substitute_teacher'];
		$substitute->duration_type = $this->transformDurationTypeFromStringToInt($input['duration_type']);

		if($substitute->duration_type === 1)
		{
			$substitute->am_pm = $input['half_day']['am_pm'];
			$substitute->from = Carbon::createFromFormat('Y-m-d', $input['half_day']['date']);
			$substitute->to = Carbon::createFromFormat('Y-m-d', $input['half_day']['date']);
		}
		elseif($substitute->duration_type === 2)
		{
			$substitute->from = Carbon::createFromFormat('Y-m-d', $input['full_day']['from_date']);
			$substitute->to = Carbon::createFromFormat('Y-m-d', $input['full_day']['to_date']);
		}
		elseif($substitute->duration_type === 3)
		{
			$substitute->from = Carbon::createFromFormat('Y-m-d', $input['period']['date']);
			$substitute->to = Carbon::createFromFormat('Y-m-d', $input['period']['date']);			
		}		

		Auth::user()->substitute()->save($substitute);

		if($substitute->duration_type === 3)
		{
			$substitute->periods()->sync($input['period']['periods']);
		}
	}

	private function transformDurationTypeFromStringToInt($durationType)
	{
		if ($durationType === 'half_day')
		{
			return 1;
		}
		elseif($durationType === 'full_day')
		{
			return 2;
		}
		elseif($durationType === 'period')
		{
			return 3;
		}

		//TODO:check when the type is invalid
		return 0;
	}
}