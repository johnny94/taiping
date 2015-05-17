<?php namespace App\Taiping\CurriculumRenderer;

class NoCurriculumRenderer implements CurriculumRenderer {

	public function render($leave) {
			
		$html = '<div class="panel panel-default panrl-no-curriculum">
					<ul class="list-group">
						<a href="#" class="list-group-item">
							<h3 class="list-group-item-heading">無課務</h3>
							<p class="list-group-item-text">請假時間 ─ <small> %s 至 %s </small></p> 
						</a>
					</ul>
				</div>';

		return sprintf($html, $leave->from, $leave->to);
	}

}