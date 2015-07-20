<?php namespace App\Http\Controllers;

use \Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use DB;
use Request;

use App\User;

class ManagerController extends Controller {

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

	public function exportLeaveDeletionLog()
	{		
		$date = $this->createDatePeriod(Request::input('start'), Request::input('end'));

		$rows = DB::table('delete_leave_log')
					  ->join('users as manager', 'delete_leave_log.manager_id', '=', 'manager.id')
					  ->join('leaves', 'delete_leave_log.leave_id', '=', 'leaves.id')
					  ->join('leavetypes', 'leaves.type_id', '=', 'leavetypes.id')
					  ->join('users as deletedUser', 'leaves.user_id', '=', 'deletedUser.id')
					  ->where('leaves.from', '>=', $date['start'])
					  ->where('leaves.from', '<=', $date['end'])
					  ->orWhere( function($query) use ($date) {

					  		$query->where('leaves.to', '>=', $date['start'])
					  		      ->where('leaves.to', '<=', $date['end']);
					  })
					  ->select('manager.name as manager', 'deletedUser.name as user', 'leavetypes.title', 'leaves.from', 'leaves.to', 'leaves.deleted_at')->get();

		$data = $this->queryResultToExportData($rows);

		\Excel::create('刪除請假日誌', function($excel) use($data) {
			
			$excel->sheet('Excel sheet', function($sheet) use($data) {

				$sheet->row(1,				
            			array('管理者', '請假人員', '假別', '請假時間(起)', '請假時間(訖)', '刪除時間'));

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
