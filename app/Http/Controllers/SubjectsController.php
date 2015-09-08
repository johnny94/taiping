<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;

use App\ClassTitle;

class SubjectsController extends Controller {

	public function __construct() {
		$this->middleware('manager');
	}

	public function index() {
		return view('manager.subjects');
	}

	public function fetchAllSubjects() {
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$subject = ClassTitle::where('title', 'like', '%' . $searchPhrase . '%');
		$total = $subject->count();
		$result = $subject->skip($currentPage*$rowCount - $rowCount)
						->take($rowCount)						
						->get();

		$response = ['current' => $currentPage, 'rowCount' => $rowCount, 'rows' => $result, 'total' => $total];
		
		return $response;
	}

	public function store() {		
		if(!Request::has('subject') || trim(Request::input('subject')) === '') {
			return abort(403, 'Invalid input.');
		}

		$subject = trim(Request::input('subject'));
		ClassTitle::create(['title' => $subject]);

		return 'success';
	}

	public function update($id) {		
		
		if(!Request::has('newSubject') || trim(Request::input('newSubject')) === '') {
			return abort(403, 'Invalid input.');
		}

		$subject = ClassTitle::findOrFail($id);
		$subject->title = trim(Request::input('newSubject'));
		$subject->save();

		return 'success';
	}

	public function destroy($id) {
		$subject = ClassTitle::findOrFail($id);
		$subject->delete();

		return 'success';
	}

}
