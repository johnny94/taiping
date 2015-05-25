<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeaveRequest;

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
use App\Leave;

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

	public function listLeaves()
	{
		$leaves = Auth::user()->leaves->sortByDesc('from');
		return view('leaves.list', compact('leaves'));
	}

	public function showCurriculums($id)
	{
		$leave = Leave::findOrFail($id);
		if ($leave->curriculum_id === 2)
		{			
			return view('classSwitchings.show', 
				['switchings' => $leave->classSwitchings]);
		} elseif ($leave->curriculum_id === 3) {
			return view('substitutes.show', 
				['substitutes' => $leave->substitutes]);
		}

		return redirect()->back();		
	}

	public function all()
	{
		
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$sort = Request::input('sort');
		$sort = each($sort);
		$sortedColumn = 'leaves.';
		if($sort['key'] === 'from' || $sort['key'] === 'to') {
			$sortedColumn .= $sort['key'];
		} elseif($sort['key'] === 'leavetype') {
			$sortedColumn = 'leavetypes.title';
		} elseif($sort['key'] === 'curriculum') {
			$sortedColumn = 'curriculums.title';
		} elseif($sort['key'] === 'name') {
			$sortedColumn = 'users.name';
		}

		$query = DB::table('users')
				->join('leaves', 'users.id', '=', 'leaves.user_id')
				->join('leavetypes', 'leaves.type_id', '=', 'leavetypes.id')
				->join('curriculums', 'leaves.curriculum_id', '=', 'curriculums.id')				
				->where('users.name', 'LIKE', "%{$searchPhrase}%")
				->orderBy($sortedColumn, $sort['value'])
				->select('users.name', 'leaves.id as leaveID', 'leaves.from', 'leaves.reason', 'leaves.to', 'leavetypes.title as leavetype', 'curriculums.title as curriculum');
		$total = count($query->get());
		$result = $query->skip( $currentPage*$rowCount - $rowCount )
						->take($rowCount)
						->get();

		$response = ['current' => $currentPage, 'rowCount' => $rowCount, 'rows' => $result, 'total' => $total];

		return $response;
	}

	public function create()
	{
		$leaveTypes = LeaveType::lists('title', 'id');
		$curriculum = Curriculum::lists('title', 'id');
		return view('leaves.create_leave', compact('leaveTypes', 'curriculum'));
	}

	public function delete($id)
	{
		$leave = Leave::findOrFail($id);
		$leave->delete();
	}

	public function createLeaveStep1(CreateLeaveRequest $request)
	{	
		
		$date = $this->createDateStringFromRequest($request);
		if ($this->applyLeaveOnSameDate($date)) {
			return redirect()->back()
							 ->withInput()
							 ->withErrors(
							 	['Invalid Date'=>'不可在同時段請假！']);
		}

		$curriculum = $request->input('curriculum');

		Session::put('leave', $request->all());
		
		if ($curriculum == Leave::NO_CURRICULUM) {
			$this->leaveApplication->applyProcedure();			
			return redirect('classes');
		} elseif ($curriculum == Leave::CLASS_SWITCHING) {			
			return redirect('classSwitchings/create');
		} elseif ($curriculum == Leave::SUBSTITUTE) {
			return redirect('substitutes/create');
		}				
	}

	private function createDateStringFromRequest($request)
	{
		return sprintf('%s %s', 
			$request->input('from_date'), 
			$request->input('from_time'));
	}

	private function applyLeaveOnSameDate($date) {
		return DB::table('leaves')->where('from', $date)
								  ->where('user_id', Auth::user()->id)
								  ->count() != 0;
	}

	public function updateClassSwitchings($id)
	{
		Session::put('leaveID', $id);
		return redirect('classSwitchings/create');
	}


	public function getTeacherNames()
	{		
		$query = Request::input('q');
		return User::where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();
	}

}
