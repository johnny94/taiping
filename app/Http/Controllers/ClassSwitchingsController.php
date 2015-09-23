<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClassSwitchingRequest;

use \Auth;
use Carbon\Carbon;
use DB;
use Request;

use App\ClassSwitching;
use App\ClassTitle;
use App\Period;
use App\User;

use App\Taiping\Repositories\ClassSwitchingRepository;
use App\Taiping\Helper\Helper;

class ClassSwitchingsController extends Controller 
{
	private $classSwitchingRepo;

	public function __construct(ClassSwitchingRepository $repository)
	{		
		$this->middleware('auth');
		$this->classSwitchingRepo = $repository;
	}

	public function show($id)
	{		
		$switching = $this->classSwitchingRepo->findOrFail($id);
		return view('classSwitchings.show', compact('switching'));
	}

	public function notChecked()
	{
		$rejectedSwitchings = $this->classSwitchingRepo->getRejected(Auth::user());
		$pendingSwitchings = $this->classSwitchingRepo->getPending(Auth::user());
		$pendingSwitchingsFromOthers = $this->classSwitchingRepo->getPendingFromOthers(Auth::user());

		$notPassSwitchings = compact('pendingSwitchings','rejectedSwitchings','pendingSwitchingsFromOthers');

		return view('classSwitchings.notChecked', $notPassSwitchings);
	}

	public function create()
	{		
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');
		// TODO: $this->app->make['App\ClassSwitching'] ?
		$switching = new ClassSwitching(['user_id' => Auth::user()->id]);
		
		return view('classSwitchings.create', compact('periods', 'classes', 'switching'));
	}

	public function edit($id)
	{		
		$switching = $this->classSwitchingRepo->findOrFail($id);
		$periods = Period::lists('name', 'id');
		$classes = ClassTitle::lists('title', 'id');

		return view('classSwitchings.edit', compact('switching', 'periods', 'classes'));
	}

	public function store(CreateClassSwitchingRequest $request)	
	{		
		$classSwitchings = $request->input('classSwitching');
		
		foreach ($classSwitchings as $switching) {			
			$classSwitching = $this->createClassSwitchingFromRequest($switching);
			Auth::user()->classSwitching()->save($classSwitching);
		}

		return redirect('classes');
	}

	public function update($id)
	{
		$switching = $this->createClassSwitchingFromRequest(Request::input('classSwitching')[0]);
		$this->classSwitchingRepo->findOrFail($id)->update($switching->toArray());
		
		return redirect('classes');
	}

	private function createClassSwitchingFromRequest($request)
	{
		$switching = new ClassSwitching;
		$switching->with_user_id = $request['teacher'];
		$switching->from = $request['from_date'];		
		$switching->from_period = $request['from_period'];
		$switching->from_class_id = $request['from_class'];
		$switching->to = $request['to_date'];
		$switching->to_period = $request['to_period'];
		$switching->to_class_id = $request['to_class'];
		$switching->checked_status_id = ClassSwitching::CHECKING_STATUS_PENDING;

		return $switching;
	}

	public function updateStatus($id)
	{
		if (!Request::has('status')) {
			return redirect('classes');
		}

		$status = Request::input('status');
		$newStatus = $status === 'pass' ? ClassSwitching::CHECKING_STATUS_PASS : ClassSwitching::CHECKING_STATUS_REJECT;
		$this->classSwitchingRepo->findOrFail($id)->update(['checked_status_id' => $newStatus]);

		return redirect('classes');
	}	

	public function destroy($id)
	{
		$classSwitching = $this->classSwitchingRepo->findOrFail($id);
		if ($classSwitching->user_id === Auth::user()->id) {
			$classSwitching->delete();			
		}

		return redirect('classSwitchings/notChecked');
	}

	public function destroyByAdmin($id)
	{
		$switching = $this->classSwitchingRepo->findOrFail($id);
		if ($switching->delete()) {
			$this->logSwitchingDeletion(Auth::user()->id, $id);
			return ['message' => true];
		}

		return abort(500);
	}

	private function logSwitchingDeletion($managerID, $switchingID)
	{
		DB::table('delete_switching_log')->insert([
				'manager_id' => $managerID,
				'switching_id' => $switchingID,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
	}

	public function search()
	{
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));		

		$query = Helper::buildClassSwitchingQuery(
							Request::input('searchPhrase'), 
							Request::input('filterByDate'), 
							Request::input('filterFrom'), 
							Request::input('filterTo'));

		$total = $query->count();
		$result = $query->skip($currentPage*$rowCount - $rowCount)
						->take($rowCount)
						->get();

		// -1 indicates that the user want to fetch all data without pagination.
		if($rowCount == -1) {
			$result = $query->get();
		}		

		$response = ['current' => $currentPage, 'rowCount' => $rowCount, 'rows' => $result, 'total' => $total];

		return $response;

	}
}
