<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Taiping\Repositories\ClassSwitchingRepository;

class ClassesController extends Controller {

	protected $classSwitchingRepo;

	public function __construct(ClassSwitchingRepository $repository)
	{
		$this->middleware('auth');
		$this->classSwitchingRepo = $repository;
	}

	public function index()
	{		
		$passedSwitchingsFromOthers = $this->classSwitchingRepo->getPassedFromOthersLastMonthByUser(Auth::user());
		$passedSwitchings = $this->classSwitchingRepo->getPassedLastMonthByUser(Auth::user());

		return view('classes.index', compact('passedSwitchingsFromOthers', 'passedSwitchings'));		
	}

}
