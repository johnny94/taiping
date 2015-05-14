<?php namespace App\Http\Controllers;

use \Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Period;
use App\Substitute;

use App\Taiping\LeaveProcedure\SubstituteProcedure;

class SubstitutesController extends Controller {

	private $leaveProcedure;

	public function __construct(SubstituteProcedure $leaveProcedure)
	{
		$this->leaveProcedure = $leaveProcedure;
		$this->middleware('auth');
	}

	public function show($id)
	{
		$substitute = Substitute::find($id);

		return view('substitutes.show', compact('substitute'));
	}

	public function create()
	{
		$periods = Period::lists('name', 'id');
		return view('substitutes.create', compact('periods'));
	}

	public function store()
	{
		$this->leaveProcedure->applyProcedure();
		return redirect('classes');
	}
}
