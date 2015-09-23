<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Carbon\Carbon;
use DB;
use Request;

use App\User;
use App\Role;

class UsersController extends Controller 
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function search()
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

	public function searchByName()
	{
		$query = trim(Request::input('q'));
		return User::where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();
	}

	public function destroy($id)
	{
		$user = User::findOrFail($id);
		if ($user->delete()) {
			$this->logDeletion(Auth::user()->id, $id);
			return ['message' => true];
		}

		return abort(500);
	}

	public function setAsManager()
	{
		$user = User::find(Request::input('teacher'));

		if (is_null($user)) {
			flash()->error('找不到這位使用者。');
			return redirect()->back();
		}

		$this->storeManagerRole($user);

		return redirect()->back();
	}

	private function storeManagerRole($user)
	{
		if ($user->isManager()) {
			flash($user->name . ' 已經是管理者。');
		} else {			
			DB::table('role_user')->insert([
				'role_id' => Role::ADMIN,
				'user_id' => $user->id,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);

			flash()->success('成功將 ' . $user->name . ' 設為管理者。');
		}
	}

	private function logDeletion($managerID, $userID)
	{
		DB::table('delete_user_log')->insert([
				'manager_id' => $managerID,
				'user_id' => $userID,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
	}
}
