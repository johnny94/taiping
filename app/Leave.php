<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Taiping\CurriculumRenderer;

class Leave extends Model {

	public function substitutes()
	{
		return $this->hasMany('App\Substitute');
	}

	public function classSwitchings()
	{
		return $this->hasMany('App\ClassSwitching');
	}

	public function user()
	{
		return $this->belongsTo('App\User');

	}

	public function renderCurriculum()
	{
		$renderer = $this->getRenderer();
		return $renderer->render($this);
	}

	private function getRenderer()
	{
		$curriculumType = $this->attributes['curriculum_id'];
		if ($curriculumType === 1) {
			return new CurriculumRenderer\NoCurriculumRenderer();			
		}

		if ($curriculumType === 2) {
			return new CurriculumRenderer\ClassSwitchingRenderer();
		}

		if ($curriculumType === 3) {
			return new CurriculumRenderer\SubstituteRenderer();
		}

		return 'error';
	}
	
}
