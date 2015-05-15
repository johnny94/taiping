<?php namespace App\Http\Controllers;

use \Auth;
use Request;
use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\ClassSwitching;
use App\ClassTitle;
use App\Period;

use App\Taiping\LeaveProcedure\ClassSwitchingProcedure;

class ClassSwitchingsController extends Controller {

	private $leaveApplication;

	public function __construct(ClassSwitchingProcedure $leaveApplication)
	{
		$this->leaveApplication = $leaveApplication;
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
		$this->leaveApplication->applyProcedure();
		return redirect('classes');
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

	public function destroy($id)
	{
		$classSwitching = ClassSwitching::findOrFail($id);
		if ($classSwitching->user_id === Auth::user()->id) {
			$classSwitching->delete();			
		}

		return redirect('classSwitchings/notChecked');

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
