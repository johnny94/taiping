<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;
use \Auth;

class PasswordController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('auth.reset', ['token' => csrf_token()]);
	}

	public function reset(Request $request)
	{
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required',
			'password' => 'required|confirmed',
		]);

		if ($this->isUserExist($request['email'])) {

			$user = User::where('email', $request['email'])->first();
			$user->password = bcrypt($request['password']);
			$user->save();
			Auth::login($user);

			return redirect('/classes');
		}		

		return redirect()->back()
			->withInput($request->only('email'))
			->withErrors(['email' => '帳號輸入有誤！']);
		
	}

	private function isUserExist($email)
	{
		return User::where('email', $email)->count() != 0 ;
	}

}
