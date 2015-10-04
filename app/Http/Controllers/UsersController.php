<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App;
use Auth;
use Carbon\Carbon;
use DB;

use App\User;
use App\Role;

class UsersController extends Controller
{
	private $user;

	public function __construct(User $user)
	{
		$this->middleware('auth');
		$this->user = $user;
	}

	public function search(Request $request)
	{
		$bootgrid = App::make('App\Taiping\Bootgrid\QueryByColumn', [$this->user, $request]);
		return $bootgrid->response();
	}

	public function searchByName(Request $request)
	{
		$query = trim($request->input('q'));
		return $this->user->where('name', 'LIKE', "%{$query}%")->select('id', 'name')->get();
	}

	public function destroy($id)
	{
		$user = $this->user->findOrFail($id);
		if ($user->delete()) {
			$this->logDeletion(Auth::user()->id, $id);
			return ['message' => true];
		}

		return abort(500);
	}

	public function setAsManager(Request $request)
	{
		$user = $this->user->find($request->input('teacher'));

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
