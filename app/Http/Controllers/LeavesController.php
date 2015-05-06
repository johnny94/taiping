<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Session;
use Request;
use DB;

use App\Leave;
use App\ClassSwitching;
use App\Substitute;
use App\CheckedStatus;
use App\Period;
use App\ClassTitle;
use App\User;
use App\LeaveType;
use App\Curriculum;

class LeavesController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('manager', ['only' => 'index']);
	}

	public function index()
	{
		return view('leaves.all');
	}

	public function all()
	{
		$queryResult = DB::table('users')
				->join('leaves', 'users.id', '=', 'leaves.user_id')
				->join('leavetypes', 'leaves.type_id', '=', 'leavetypes.id')
				->join('curriculums', 'leaves.curriculum_id', '=', 'curriculums.id')
				->leftJoin('class_switchings', 'leaves.id', '=', 'class_switchings.leave_id')
				->leftJoin('substitutes', 'leaves.id', '=', 'substitutes.leave_id')
				->select('users.name', 'leaves.from', 'leaves.to', 'leavetypes.title as leavetype', 'curriculums.title as curriculum', 'class_switchings.id as switchingID', 'substitutes.id as substituteID')
				->get();

		$response = ['current' => 1, 'rowCount' => 10, 'rows' => $queryResult, 'total' => count($queryResult)];

		return $response;		
	}

	public function create()
	{
		$leaveTypes = LeaveType::lists('title', 'id');
		$curriculum = Curriculum::lists('title', 'id');
		return view('leaves.create_leave', compact('leaveTypes', 'curriculum'));
	}

	public function createLeaveStep1()
	{
		$curriculum = Request::input('curriculum');

		Session::put('leave', Request::all());
		
		if ($curriculum === '1') {
			$leave = $this->createLeaveFromSession();		
			return redirect('classes');
		} elseif ($curriculum === '2') {			
			return redirect('leaves/switching/create');
		} elseif ($curriculum === '3') {
			return redirect('leaves/substitute/create');
		}
		
	}

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

	public function createLeaveWithSwitching()
	{		
		//TODO: prevent from creating switching without creating leave
		$leave = $this->createLeaveFromSession();

		//TODO: from_class and to_class are not work properly (need to create the class table in DB)
		$classSwitchings = Request::input('classSwitching');
		foreach ($classSwitchings as $switching) {
			$class_switching = new ClassSwitching;
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

		return redirect('classes');
	}

	public function editSwitching($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');
		return view('leaves.edit_switching', compact('switching', 'periods', 'classes'));
	}

	public function passSwitching($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		$switching->checked_status_id = DB::table('checked_status')->where('title', 'pass')->first()->id;
		$switching->save();

		return redirect('classes');
	}

	public function rejectSwitching($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		$switching->checked_status_id = DB::table('checked_status')->where('title', 'reject')->first()->id;
		$switching->save();

		return redirect('classes');
	}

	public function updateSwitching($id)
	{
		$oldSwitching = ClassSwitching::findOrFail($id);

		$switching = Request::input('classSwitching')[0];
		$class_switching = new ClassSwitching;		
		$class_switching->with_user_id = $switching['teacher'];
		$class_switching->from = Carbon::createFromFormat('Y-m-d', $switching['from_date']);
		$class_switching->from_period = intval($switching['from_period']);
		$class_switching->from_class_id = intval($switching['from_class']);
		$class_switching->to = Carbon::createFromFormat('Y-m-d', $switching['to_date']);
		$class_switching->to_period = intval($switching['to_period']);
		$class_switching->to_class_id = intval($switching['to_class']);
		$class_switching->checked_status_id = DB::table('checked_status')->where('title', 'pending')->first()->id;

		$oldSwitching->update($class_switching->toArray());

		return redirect('classes');

	}

	public function createLeaveWithSubstitute()
	{
		//TODO: prevent from creating substitute without creating leave
		//return Request::all();
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

	public function uncheckedSwitching()
	{
		$rejectedSwitchings = Auth::user()->classSwitching->filter(function($item){
			return $item->checked_status_id == 3; //id = 3 reject by other teacher.
		});

		$pendingSwitchings = Auth::user()->classSwitching->filter(function($item){
			return $item->checked_status_id == 1; //id = 1 pending.
		});		

		$pendingSwitchingsFromOthers = Auth::user()->withClassSwitching->filter(function($item){
			return $item->checked_status_id == 1; //id = 1 pending.
		});

		$notPassSwitchings = compact('pendingSwitchings','rejectedSwitchings','pendingSwitchingsFromOthers');

		return view('leaves.unchecked_switchings', $notPassSwitchings);
	}

	public function substitute($id)
	{
		$substitute = Substitute::find($id);

		return view('leaves.substitute', compact('substitute'));
	}

	public function createSubstitute()
	{
		$periods = Period::lists('name', 'id');
		return view('leaves.create_substitute', compact('periods'));
	}

	public function switching($id)
	{
		$switching = ClassSwitching::find($id);
		return view('leaves.switching', compact('switching'));
	}

	public function createSwitching()
	{
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');

		return view('leaves.create_switching', compact('periods', 'classes'));
	}

	public function getTeacherNames()
	{
		$query = Request::input('q');
		return User::where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();		
	}

}
