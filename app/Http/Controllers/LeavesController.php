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
			return redirect('classSwitchings/create');			
		} elseif ($curriculum === '3') {
			return redirect('substitutes/create');
		}
				
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

	public function getTeacherNames()
	{
		$query = Request::input('q');
		return User::where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();		
	}

}
