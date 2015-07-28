<?php namespace App\Http\Controllers;

use \Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use DB;
use Request;

use App\User;
use App\ClassSwitching;

class ManagerController extends Controller {

	
	public function __construct()
	{
		$this->middleware('manager');
	}

	public function switchings()
	{
		return view('manager.switchings');
	}

	public function fetchSwitchings() {

		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));		

		$query = $this->buildSwitchingQuery(
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

	private function buildSwitchingQuery($searchPhrase, $filterByDate, $filterFrom, $filterTo) {
		$query = DB::table('class_switchings')
				    ->join('users as from_user', 'class_switchings.user_id', '=', 'from_user.id')
					->join('periods as from_period', 'class_switchings.from_period', '=', 'from_period.id')
					->join('classtitles as from_class', 'class_switchings.from_class_id', '=', 'from_class.id')
					->join('users as with_user', 'class_switchings.with_user_id', '=', 'with_user.id')
					->join('periods as to_period', 'class_switchings.to_period', '=', 'to_period.id')
					->join('classtitles as to_class', 'class_switchings.to_class_id', '=', 'to_class.id')
					->join('checked_status', 'class_switchings.checked_status_id', '=', 'checked_status.id')
					->whereNull('class_switchings.deleted_at')
					->where(function($q) use($searchPhrase) {
						$q->where('from_user.name', 'LIKE', "%{$searchPhrase}%")
						  ->orWhere('with_user.name', 'LIKE', "%{$searchPhrase}%");
					});
		
		if($filterByDate === 'true') {
			$date = $this->createDatePeriod($filterFrom, $filterTo);

			$query = $query->where(
						function($query) use($date){

							$query->where(function($query) use($date) {
										$query->where('class_switchings.from', '>=', $date['start'])
									  		  ->where('class_switchings.from', '<=', $date['end']);
									})
									->orWhere(function($query) use($date) {
					       				$query->where('class_switchings.to', '>=', $date['start'])
					  		          	      ->where('class_switchings.to', '<=', $date['end']);
									});
						});		
		}

		$query = $query->select('class_switchings.id', 'from_user.name as teacher', 'from_period.name as from_period', 'from_class.title as from_class', 'class_switchings.from', 'with_user.name as with_teacher', 'to_period.name as to_period', 'to_class.title as to_class', 'class_switchings.to', 'checked_status.title as status');

		return $query;
	}

	public function exportSwitchingLog() {		
		$rows = $this->buildSwitchingQuery(
							Request::input('searchPhrase'),
							Request::input('filterByDate'), 
							Request::input('filterFrom'), 
							Request::input('filterTo'))->get();

		$data = $this->queryResultToExportData($rows);

		\Excel::create('調課紀錄日誌', function($excel) use($data) {
			
			$excel->sheet('Excel sheet', function($sheet) use($data) {

				$sheet->row(1,				
            			array('ID', '調課老師', '上課日期', '科目', '節次', '被調課老師', '上課日期', '科目', '節次', '調課情況'));

				$sheet->fromArray(
					$data, null, 'A2', false, false);
    		});

		})->export('xls');

	}

	public function deleteSwitching($id)
	{
		$switching = ClassSwitching::findOrFail($id);
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

	public function users()
	{
		return view('manager.users');
	}

	public function fetchRegisteredUser()
	{
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$sort = Request::input('sort');
		$sort = each($sort);
		
		$users = User::where('name', 'like', '%' . $searchPhrase . '%');
		$total = $users->count();
		$result = $users->orderBy($sort['key'], $sort['value'])->skip($currentPage*$rowCount - $rowCount)
						->take($rowCount)						
						->get();

		$response = ['current' => $currentPage, 'rowCount' => $rowCount, 'rows' => $result, 'total' => $total];
		
		return $response;
	}

	public function deleteUser($id)
	{
		$user = User::findOrFail($id);
		if ($user->delete()) {
			$this->logUserDeletion(Auth::user()->id, $id);
			return ['message' => true];
		}

		return abort(500);
	}

	private function logUserDeletion($managerID, $userID)
	{
		DB::table('delete_user_log')->insert([
				'manager_id' => $managerID,
				'user_id' => $userID,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
	}

	public function setManager()
	{
		return view('manager.setAsManager');
	}

	public function setAsManager()
	{
		$user = User::find(Request::input('teacher'));

		if (is_null($user)) {
			flash()->error('找不到這位使用者。');
			return redirect()->back();
		}

		return $this->storeManagerRole($user);		
	}

	private function storeManagerRole($user)
	{
		if ($user->isManager()) {
			flash($user->name . ' 已經是管理者。');			
		} else {			
			DB::table('role_user')->insert([
				'role_id' => 2,
				'user_id' => $user->id
			]);

			flash()->success('成功將 ' . $user->name . ' 設為管理者。');
		}

		return redirect()->back();
	}

	public function exportLog()
	{
		return view('manager.exportLog');
	}

	public function exportSwitchingDeletionLog()
	{		
		$date = $this->createDatePeriod(Request::input('start'), Request::input('end'));

		$rows = DB::table('delete_switching_log as log')
					  ->join('users as manager', 'log.manager_id', '=', 'manager.id')
					  ->join('class_switchings as switching', 'log.switching_id', '=', 'switching.id')
					  ->join('users as from_user', 'switching.user_id', '=', 'from_user.id')
					  ->join('periods as from_period', 'switching.from_period', '=', 'from_period.id')
					  ->join('classtitles as from_class', 'switching.from_class_id', '=', 'from_class.id')
					  ->join('users as with_user', 'switching.with_user_id', '=', 'with_user.id')
					  ->join('periods as to_period', 'switching.to_period', '=', 'to_period.id')
					  ->join('classtitles as to_class', 'switching.to_class_id', '=', 'to_class.id')
					  ->join('checked_status', 'switching.checked_status_id', '=', 'checked_status.id')
					  ->where(function($query) use($date) {

					  		$query->where('switching.from', '>=', $date['start'])
								  ->where('switching.from', '<=', $date['end']);
					  })
					  ->orWhere(function($query) use($date) {

					       	$query->where('switching.to', '>=', $date['start'])
					  		      ->where('switching.to', '<=', $date['end']);

					  })
					  ->select('manager.name', 'from_user.name as teacher', 'switching.from', 'from_class.title as from_class', 'from_period.name as from_period', 'with_user.name as with_teacher', 'switching.to', 'to_class.title as to_class', 'to_period.name as to_period', 'checked_status.title as status', 'switching.deleted_at')->get();

		$data = $this->queryResultToExportData($rows);

		\Excel::create('刪除請假日誌', function($excel) use($data) {
			
			$excel->sheet('Excel sheet', function($sheet) use($data) {

				$sheet->row(1,				
            			array('管理者(刪除人)', '調課老師', '上課日期', '科目', '節次', '被調課老師', '上課日期', '科目', '節次', '調課情況', '刪除時間'));

				$sheet->fromArray(
					$data, null, 'A2', false, false);
    		});

		})->export('xls');
	}

	private function createDatePeriod($start, $end)
	{
		// To create the date between Y-m-d 00:00:00 and Y-m-d 23:59:59.
		$start = Carbon::createFromFormat('Y-m-d H' ,sprintf('%s 0', $start));
		$end = Carbon::createFromFormat('Y-m-d H' ,sprintf('%s 0', $end))->addDay()->subSecond();

		return ['start'=>$start, 'end'=>$end];
	}

	private function queryResultToExportData($rows)
	{
		$data = [];
		foreach ($rows as $row) {
			$data[] = array_values((array)$row);			
		}

		return $data;
	}

	public function exportUserDeletionLog()
	{
		$rows = DB::table('delete_user_log')
					  ->join('users as manager', 'delete_user_log.manager_id', '=', 'manager.id')
					  ->join('users as deletedUser', 'delete_user_log.user_id', '=', 'deletedUser.id')
					  ->select('manager.name as manager', 'deletedUser.name as user', 'deletedUser.email', 'deletedUser.created_at', 'deletedUser.deleted_at')->get();

		$data = $this->queryResultToExportData($rows);

		\Excel::create('刪除帳號日誌', function($excel) use($data) {
			
			$excel->sheet('Excel sheet', function($sheet) use($data) {

				$sheet->row(1,				
            			array('管理者', '使用者姓名', 'E-Mail', '註冊時間', '刪除時間'));

				$sheet->fromArray($data, null, 'A2', false, false);
    		});

		})->export('xls');
	}

}
