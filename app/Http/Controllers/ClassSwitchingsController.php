<?php namespace App\Http\Controllers;

use \Auth;
use Session;
use Request;
use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\ClassSwitching;
use App\ClassTitle;
use App\Leave;
use App\Period;



class ClassSwitchingsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function show($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		return view('classSwitchings.show', compact('switching'));
	}

	public function create()
	{		
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');

		return view('classSwitchings.create', compact('periods', 'classes'));
	}

	public function edit($id)
	{		
		$switching = ClassSwitching::findOrFail($id);
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');
		return view('classSwitchings.edit', compact('switching', 'periods', 'classes'));
	}

	public function store()
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

	public function update($id)
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

	public function pass($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		$switching->checked_status_id = DB::table('checked_status')->where('title', 'pass')->first()->id;
		$switching->save();

		return redirect('classes');
	}

	public function reject($id)
	{
		$switching = ClassSwitching::findOrFail($id);
		$switching->checked_status_id = DB::table('checked_status')->where('title', 'reject')->first()->id;
		$switching->save();

		return redirect('classes');
	}

	public function notChecked()
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

		return view('classSwitchings.notChecked', $notPassSwitchings);
	}

}
