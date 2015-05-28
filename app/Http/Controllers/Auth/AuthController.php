<?php namespace App\Http\Controllers\Auth;

use Mail;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use App\User;
use Carbon\Carbon;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */

	protected $redirectTo = 'classes';

	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister(Request $request)
	{
		$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}

		// E-Mail activation

		$activationCode = hash_hmac('sha256', str_random(40), env('APP_KEY'));		

		Mail::send('emails.account', compact('activationCode'), function($message) use($request) {
		    $message->to($request->input('email'), $request->input('name'))
		    	    ->subject('Welcome!');
		});
		
		$user = new User;
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->password = bcrypt($request->input('password'));
		$user->save();
		
		$payLoad = ['email' => $request->input('email'), 
		            'activation_code' => $activationCode, 
		            'created_at' => new Carbon];

		DB::table('account_confirm')->insert($payLoad);
		
		flash()->success('註冊成功！認證信已寄送至：' . $request->input('email'));

		return redirect('/auth/login');
	}

	public function activateAccount($code)
	{		
		$activateUser = User::from('users')->whereExists(function($query) use($code) {
			$query->select(DB::raw(1))
				  ->from('account_confirm')
				  ->where('account_confirm.email', '=', 'users.email')
				  ->where('account_confirm.activation_code', '=', $code);
		})->first();

		dd($activateUser);

		$this->auth->login($activateUser);

		return redirect('/classes');

	}

}
