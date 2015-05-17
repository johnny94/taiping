<?php namespace App\Taiping\CurriculumRenderer;

class SubstituteRenderer implements CurriculumRenderer {

	public function render($leave) {
		$substitutes = $leave->substitutes;
		$html = '<div class="panel panel-default panel-substitute">
  					<ul class="list-group">
    					<a href="%s" class="list-group-item">
							<h3 class="list-group-item-heading">請代課老師 <small>%s 至 %s</small></h3>
	    					<p class="list-group-item-text">代課老師 ─ <mark>%s</mark></p>
						</a>  
  					</ul>  				
				</div>';
		$result = '';

		foreach ($substitutes as $substitute) {
			$link = action('SubstitutesController@show', [$substitute->id]);			
			$result .= sprintf($html, $link, $substitute->from, $substitute->to, $substitute->substitute_teacher);
			
		}

		return $result;
	}

}