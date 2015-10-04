<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App;

use App\Period;

class PeriodsController extends Controller
{
	private $period;

	public function __construct(Period $p)
	{
		$this->middleware('manager');
		$this->period = $p;
	}

	public function search(Request $request)
	{
		$bootgrid = App::make('App\Taiping\Bootgrid\QueryByColumn', [$this->period, $request]);
		$response = $bootgrid->response();
		return $response;
	}

	public function store(Request $request)
	{
		$period = trim($request->input('period', ''));
		if($period === '') {
			return abort(403, 'Invalid input.');
		}

		$this->period->create(['name' => $period]);

		return ['message' => 'success'];
	}

	public function update(Request $request, $id)
	{
		$newPeriod = trim($request->input('newPeriod', ''));
		if($newPeriod === '') {
			return abort(403, 'Invalid input.');
		}

		$period = $this->period->findOrFail($id);
		$period->name = $newPeriod;
		$period->save();

		return 'success';
	}

	public function destroy($id)
	{
		$period = $this->period->findOrFail($id);
		$period->delete();

		return ['message' => 'success'];
	}
}
