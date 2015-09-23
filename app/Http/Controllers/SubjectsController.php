<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request;

use App\ClassTitle;

class SubjectsController extends Controller {

	private $classTitle;
	public function __construct(ClassTitle $title) {
		$this->middleware('manager');
		$this->classTitle = $title;
	}

	public function search() {
		$currentPage = intval(Request::input('current'));
		$rowCount = intval(Request::input('rowCount'));
		$searchPhrase = Request::input('searchPhrase');

		$subject = $this->classTitle->where('title', 'like', '%' . $searchPhrase . '%');
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
		$this->classTitle->create(['title' => $subject]);

		return 'success';
	}

	public function update($id) {		
		
		if(!Request::has('newSubject') || trim(Request::input('newSubject')) === '') {
			return abort(403, 'Invalid input.');
		}

		$subject = $this->classTitle->findOrFail($id);
		$subject->title = trim(Request::input('newSubject'));
		$subject->save();

		return 'success';
	}

	public function destroy($id) {
		$subject = $this->classTitle->findOrFail($id);
		$subject->delete();

		return 'success';
	}

}
