<?php namespace App\Http\Controllers\Auth;

use Mail;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use App\User;
use App\Role;
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

		$email = $request->input('email');
		$name = $request->input('name');

		Mail::queue('emails.account', compact('activationCode'), function($message) use ($email, $name) {
		    $message->to($email, $name)
		    	    ->subject('帳號認證連結');
		});

		$user = new User;
		$user->name = $name;
		$user->email = $email;
		$user->password = bcrypt($request->input('password'));
		$user->save();

		DB::table('role_user')->insert(
    		[
    			'role_id' => Role::USER,
    			'user_id' => $user->id,
    			'created_at' => new Carbon,
				'updated_at' => new Carbon
    		]
		);

		$payLoad = ['email' => $email,
		            'activation_code' => $activationCode,
		            'created_at' => new Carbon];

		DB::table('account_confirm')->insert($payLoad);

		flash()->success('註冊成功！認證信已寄送至：' . $email);

		return redirect('/auth/login');
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email|exists:users', 'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');
		$credentials['active'] = 1;

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			return redirect()->intended($this->redirectPath());
		}

		//dd($this->validate->errors());

		return redirect($this->loginPath())
					->withInput($request->only('email', 'remember'))
					->withErrors([
						'account'=> '您所輸入的電子郵件和密碼不相符，請確認輸入是否有誤。',
						'active'=> '確認是否已通過認證。'
					]);
	}


	public function activateAccount($code)
	{
		$this->auth->logout();

		$activateUser = DB::table('users')->join('account_confirm', function($join) use($code) {

			$join->on('account_confirm.email', '=', 'users.email')
			     ->where('account_confirm.activation_code', '=', $code);

		})->first();

		if (is_null($activateUser)) {
			flash()->error('無效的驗證碼或帳號不存在！');
			return redirect('/auth/login');
		}

		$user = User::findOrFail($activateUser->id);

		if (!$user->active) {
			$user->active = true;
			$user->save();
			flash('認證成功！');
		}

		DB::table('account_confirm')->where('activation_code', '=', $code)
									->where('email', '=', $user->email)->delete();

		$this->auth->login($user);

		return redirect('/classes');
	}
}
