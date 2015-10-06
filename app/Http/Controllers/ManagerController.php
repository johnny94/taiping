<?php namespace App\Http\Controllers;

use \Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;

class ManagerController extends Controller
{

	public function __construct()
	{
		$this->middleware('manager');
	}

	public function users()
	{
		return view('manager.users');
	}

	public function settings()
	{
		$managers = \App\Role::find(Role::ADMIN)->users;
		return view('manager.settings', compact('managers'));
	}

	public function switchings()
	{
		return view('manager.switchings');
	}

	public function subjects()
	{
		return view('manager.subjects');
	}

	public function periods()
	{
		return view('manager.periods');
	}

	public function exportLog()
	{
		return view('manager.exportLog');
	}
}
