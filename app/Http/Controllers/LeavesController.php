<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Session;
use Request;
use DB;

use App\CheckedStatus;
use App\Period;
use App\ClassTitle;
use App\User;
use App\LeaveType;
use App\Curriculum;

use App\Taiping\LeaveProcedure\NoCurriculumProcedure;

class LeavesController extends Controller {

	private $leaveApplication;

	public function __construct(NoCurriculumProcedure $leaveApplication)
	{
		$this->leaveApplication = $leaveApplication;
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
			$this->leaveApplication->applyProcedure();			
			return redirect('classes');
		} elseif ($curriculum === '2') {			
			return redirect('classSwitchings/create');			
		} elseif ($curriculum === '3') {
			return redirect('substitutes/create');
		}
				
	}

	public function getTeacherNames()
	{
		$query = Request::input('q');
		return User::where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();		
	}

}
