<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Leave;
use Carbon;

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

		
		$CHECK_STATUS_PASS = 2;
		$passedSwitchingsFromOthers = Auth::user()->withClassSwitching->where('checked_status_id', $CHECK_STATUS_PASS);
		$leaves = Auth::user()->leaves->filter(function($item){
			return Carbon\Carbon::now()->subMonth() <= $item->to;
		});

		foreach($passedSwitchingsFromOthers as $switching) {
			if (Carbon\Carbon::now()->subMonth() <= $switching->leave->to) {
				$leaves->push($switching->leave);
			}			
		}

		$leaves = $leaves->unique();
		$leaves->sortByDesc('from');

		return view('classes.index', compact('leaves', 'isAllSwitchingPass'));		
	}

}
