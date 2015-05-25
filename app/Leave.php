<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Taiping\CurriculumRenderer;

use DB;

class Leave extends Model {

	const NO_CURRICULUM = 1;
	const CLASS_SWITCHING = 2;
	const SUBSTITUTE = 3;

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

	// Deprecated
	public function renderCurriculum()
	{
		$renderer = $this->getRenderer();
		return $renderer->render($this);
	}

	private function getRenderer()
	{
		$curriculumType = $this->attributes['curriculum_id'];
		if ($curriculumType === self::NO_CURRICULUM) {
			return new CurriculumRenderer\NoCurriculumRenderer();			
		}

		if ($curriculumType === self::CLASS_SWITCHING) {
			return new CurriculumRenderer\ClassSwitchingRenderer();
		}

		if ($curriculumType === self::SUBSTITUTE) {
			return new CurriculumRenderer\SubstituteRenderer();
		}

		return 'error';
	}

	public function getLeaveType()
	{
		$type = DB::table('leavetypes')
					->where('id', '=', $this->attributes['type_id'])
					->get();

		if (count($type) != 0) {
			return $type[0]->title;
		}

		return 'Unknown Type';
	}

	public function getCurriculum()
	{
		$type = DB::table('curriculums')
					->where('id', '=', $this->attributes['curriculum_id'])
					->get();
					
		if (count($type) != 0) {
			return $type[0]->title;
		}

		return 'Unknown Curriculum';
	}

	protected static function boot() {
        parent::boot();

        static::deleting(function($leave) {
             
             foreach ($leave->classSwitchings as $classSwitching) {
             	$classSwitching->delete();
             }

             foreach ($leave->substitutes as $substitute) {
             	$substitute->delete();
             }
                        
        });
    }
	
}
