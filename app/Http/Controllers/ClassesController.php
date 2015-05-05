<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Leave;

class ClassesController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		
		$isAllSelfSwitchingPass = Auth::user()->classSwitching->filter(function($item){
			return $item->checked_status_id != 2; //id = 2 means the switching class has passed.
		})->isEmpty();

		$hasNoPendingSwitchingFromOther = Auth::user()->withClassSwitching->filter(function($item){
			return $item->checked_status_id == 1; //id = 1 means the switching class is pending.
		})->isEmpty();

		$isAllSwitchingPass = $isAllSelfSwitchingPass && $hasNoPendingSwitchingFromOther;

		
		$passedSwitchings = Auth::user()->classSwitching->filter(function($item){
			return $item->checked_status_id == 2;
		});

		$passedSwitchingsFromOthers = Auth::user()->withClassSwitching->filter(function($item){
			return $item->checked_status_id == 2;
		});

		$substitutes = Auth::user()->substitute;

		$leaves = Auth::user()->leaves;
		
		return view('classes.index', compact('leaves', 'isAllSwitchingPass', 'passedSwitchings', 'passedSwitchingsFromOthers','substitutes'));
	}

}
