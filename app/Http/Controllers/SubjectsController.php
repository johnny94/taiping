<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App;

use App\ClassTitle;
use App\Taiping\Bootgrid;
use App\Taiping\Helper\Helper;

class SubjectsController extends Controller
{

	private $classTitle;
	public function __construct(ClassTitle $title)
	{
		$this->middleware('manager');
		$this->classTitle = $title;
	}

	public function search(Request $request)
	{
		$bootgrid = App::make('App\Taiping\Bootgrid\QueryByColumn', [$this->classTitle, $request]);
		$response = $bootgrid->response();
		return $response;
	}

	public function store(Request $request)
	{
		if(!$request->has('subject') || trim($request->input('subject')) === '') {
			return abort(403, 'Invalid input.');
		}

		$subject = trim($request->input('subject'));
		$this->classTitle->create(['title' => $subject]);

		return ['message' => 'success'];
	}

	public function update(Request $request, $id)
	{
		if(!$request->has('newSubject') || trim($request->input('newSubject')) === '') {
			return abort(403, 'Invalid input.');
		}

		$subject = $this->classTitle->findOrFail($id);
		$subject->title = trim($request->input('newSubject'));
		$subject->save();

		return 'success';
	}

	public function destroy($id)
	{
		$subject = $this->classTitle->findOrFail($id);

		// Mark the deleted subject.
		// To distinguish the subjects from those which have not been deleted yet
		// when fetching the record from database.
		$subject->title = $subject->title . ' (已刪除)';
		$subject->save();

		$subject->delete();

		return ['message' => 'success'];
	}
}
