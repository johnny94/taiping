<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;

use App\Period;

class PeriodsController extends Controller {

	private $period;

	public function __construct(Period $p) {
		$this->middleware('manager');
		$this->period = $p;
	}	

	public function search() {
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$subject = $this->period->where('name', 'like', '%' . $searchPhrase . '%');
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
		
		$this->period->create(['name' => $period]);

		return 'success';
	}

	public function update($id) {

		$newPeriod = trim(Request::input('newPeriod', ''));
		if($newPeriod === '') {
			return abort(403, 'Invalid input.');
		}

		$period = $this->period->findOrFail($id);
		$period->name = $newPeriod;
		$period->save();

		return 'success';
	}

	public function destroy($id) {
		$period = $this->period->findOrFail($id);
		$period->delete();

		return 'success';
	}
}
