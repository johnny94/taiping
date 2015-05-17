<?php namespace App\Taiping\CurriculumRenderer;

use \Auth;

class ClassSwitchingRenderer implements CurriculumRenderer {

	public function render($leave) {		

		$classSwitchings = $leave->classSwitchings;		
		$html = '<div class="panel panel-default %s">
	  				<ul class="list-group">	  			
	    				<a href="%s" class="list-group-item">
	    					<h3 class="list-group-item-heading">調課 <small> %s </small></h3>	    			
	    					<p class="list-group-item-text">調課申請人 ─ <mark>%s</mark></p>
	    				</a>  
	  				</ul>
				</div>';
		$result = '';

		foreach ($classSwitchings as $classSwitching) {
			if ($classSwitching->isPass()) {
				$link = action('ClassSwitchingsController@show', [$classSwitching->id]);
				$date = $this->getSwitchingDate($classSwitching);
				$style = $this->getPanelStyle($classSwitching);	
				$result .= sprintf($html, $style, $link, $date, $classSwitching->switchingTeacher->name);
			}
		}

		return $result;
	}

	private function getPanelStyle($classSwitching) {
		if (Auth::user()->id === $classSwitching->switchingTeacher->id) {
			return 'panel-switching-class';
		}

		return 'panel-switching-class-others';
	}

	private function getSwitchingDate($classSwitching) {
		if (Auth::user()->id === $classSwitching->switchingTeacher->id) {
			return $classSwitching->from;

		}

		return $classSwitching->to;
	}

}