<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Leave;
use Carbon\Carbon;

class ClassesController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{		
		$CHECK_STATUS_PASS = 2;
		$passedSwitchingsFromOthers = Auth::user()->withClassSwitching
												  ->where('checked_status_id', $CHECK_STATUS_PASS)
												  ->filter(function($item){
												  	return $item->to >= Carbon::now()->subMonth();
								   				  })->sortByDesc('to');

		$passedSwitchings = Auth::user()->classSwitching
										->where('checked_status_id', $CHECK_STATUS_PASS)
										->filter(function($item){
								   			return $item->from >= Carbon::now()->subMonth();
								   		})->sortByDesc('from');

		return view('classes.index', compact('passedSwitchingsFromOthers', 'passedSwitchings'));		
	}

}
