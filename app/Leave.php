<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {

	public function substitutes()
	{
		return $this->hasMany('App\Substitute');
	}

	public function classSwitchings()
	{
		return $this->hasMany('App\ClassSwitching');
	}

	public function user()
	{
		return $this->belongsTo('App\User');

	}
}
