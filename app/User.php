<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use App\Role;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	use SoftDeletes;
    protected $dates = ['deleted_at'];

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
	protected $fillable = ['name', 'email', 'password', 'active'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'updated_at', 'deleted_at'];

	public function classSwitching()
	{
		return $this->hasMany('App\ClassSwitching', 'user_id');
	}

	public function withClassSwitching()
	{
		return $this->hasMany('App\ClassSwitching', 'with_user_id');
	}

	public function role()
	{
		return $this->belongsToMany('App\Role')->withTimestamps();
	}

	public function numberOfUncheckedClassSwitching()
	{
		$number = 0;
		$number += $this->withClassSwitching->filter(function($classSwitching){
			return !$classSwitching->isPass();
		})->count();

		$number += $this->classSwitching->filter(function($classSwitching){
			return !$classSwitching->isPass();
		})->count();

		return $number;

	}

	public function isManager()
	{
		return DB::table('role_user')
					->where('role_id', '=', Role::ADMIN) // Role id = 2 => admin
					->where('user_id', '=', $this->id)
					->count() != 0;
	}

	protected static function boot() {
        parent::boot();

        static::deleting(function($user) {

            foreach ($user->withClassSwitching as $classSwitching) {
            	$classSwitching->delete();
            }

        });
    }

}
