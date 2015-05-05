<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function leaves()
	{
		return $this->hasMany('App\Leave');
	}

	public function classSwitching()
	{
		return $this->hasMany('App\ClassSwitching', 'user_id');
	}

	public function withClassSwitching()
	{
		return $this->hasMany('App\ClassSwitching', 'with_user_id');
	}

	public function substitute()
	{
		return $this->hasMany('App\Substitute');
	}

	public function isManager()
	{
		return DB::table('role_user')
					->where('role_id', '=', 2) // Role id = 2 => manager
					->where('user_id', '=', $this->id)
					->count() != 0;
	}

}
