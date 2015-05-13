<?php namespace App\Http\Controllers;

use \Auth;
use Request;
use Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Period;
use App\Substitute;
use App\Leave;

class SubstitutesController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function show($id)
	{
		$substitute = Substitute::find($id);

		return view('substitutes.show', compact('substitute'));
	}

	public function create()
	{
		$periods = Period::lists('name', 'id');
		return view('substitutes.create', compact('periods'));
	}

	public function store()
	{
		//TODO: prevent from creating substitute without creating leave
		$leave = $this->createLeaveFromSession();		
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
		
		return redirect('classes');
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

	//TODO: move this method to a helper class
	private function createLeaveFromSession()
	{
		$leaveFromRequest = Session::get('leave', []);
		$from_date =  $leaveFromRequest['from_date'];
		$from_time =  $leaveFromRequest['from_time'];
		$to_date =  $leaveFromRequest['to_date'];
		$to_time =  $leaveFromRequest['to_time'];

		$leave = new Leave;
		$leave->from = Carbon::createFromFormat('Y-m-d H:i', "$from_date $from_time");
		$leave->to = Carbon::createFromFormat('Y-m-d H:i', "$to_date $to_time");
		$leave->type_id = $leaveFromRequest['leaveType'];
		$leave->curriculum_id = $leaveFromRequest['curriculum'];

		Auth::user()->leaves()->save($leave);

		return $leave;
	}

}
