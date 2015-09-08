<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;

use App\Period;

class PeriodsController extends Controller {

	public function __construct() {
		$this->middleware('manager');
	}

	public function index() {
		return view('manager.periods');
	}

	public function fetchAllPeriods() {
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$subject = Period::where('name', 'like', '%' . $searchPhrase . '%');
		$total = $subject->count();
		$result = $subject->skip($currentPage*$rowCount - $rowCount)
						->take($rowCount)						
						->get();

		$response = ['current' => $currentPage, 'rowCount' => $rowCount, 'rows' => $result, 'total' => $total];
		
		return $response;
	}

	public function store() {
		$period = trim(Request::input('period', ''));
		if($period === '') {
			return abort(403, 'Invalid input.');
		}
		
		Period::create(['name' => $period]);

		return 'success';
	}

	public function update($id) {

		$newPeriod = trim(Request::input('newPeriod', ''));
		if($newPeriod === '') {
			return abort(403, 'Invalid input.');
		}

		$period = Period::findOrFail($id);
		$period->name = $newPeriod;
		$period->save();

		return 'success';
	}

	public function destroy($id) {
		$period = Period::findOrFail($id);
		$period->delete();

		return 'success';
	}
}
