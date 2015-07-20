<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	const USER = 1;
	const ADMIN = 2;	

	public function users()
	{
		return $this->belongsToMany('App\User');
	}

}
