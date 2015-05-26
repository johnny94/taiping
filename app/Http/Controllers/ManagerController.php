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
		
		$total = count(User::all()->count());
		$result = User::orderBy($sort['key'], $sort['value'])->skip($currentPage*$rowCount - $rowCount)
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

		if (!is_null($user)) {
			$this->storeManagerRole($user);
		} 

		flash()->error('找不到這位使用者。');
		return redirect()->back();
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
		\Excel::create('Filename', function($excel) {
			
			// Set the title
    		$excel->setTitle('Our new awesome title');

			$excel->sheet('Excel sheet', function($sheet) {

				$sheet->fromArray(
					array(
            			array('data1', 'data2'),
            			array('data3', 'data4')
            		), null, 'A1', false, false);
    		});

		})->export('csv');

	}

}
