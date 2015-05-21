@extends('main')

@section('title')
		<div class="page-header">
  			<h1>請假與課務狀況 <small>課務列表</small></h1>
  			<a class="btn btn-default" href="/leaves/create">
  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
		</a>
		</div>
@stop
		
@section('content')		
	
	@foreach($passedSwitchingsFromOthers as $classSwitching)
		<div class="panel panel-default panel-switching-class-others">
	  		<ul class="list-group">	  			
	    		<a href="{{action('ClassSwitchingsController@show', [$classSwitching->id])}}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課 <small> {{$classSwitching->to}} </small></h3>	    			
	    			<p class="list-group-item-text">調課申請人 ─ <mark>{{$classSwitching->switchingTeacher->name}}</mark></p>
	    		</a>  
	  		</ul>
		</div>
	@endforeach

	@foreach($passedSwitchings as $classSwitching)
		<div class="panel panel-default panel-switching-class">
	  		<ul class="list-group">	  			
	    		<a href="{{action('ClassSwitchingsController@show', [$classSwitching->id])}}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課 <small> {{$classSwitching->from}} </small></h3>	    			
	    			<p class="list-group-item-text">調課申請人 ─ <mark>{{$classSwitching->switchingTeacher->name}}</mark></p>
	    		</a>  
	  		</ul>
		</div>		
	@endforeach

	@foreach($substitutes as $substitute)
		<div class="panel panel-default panel-substitute">
  			<ul class="list-group">
    			<a href="{{action('SubstitutesController@show', [$substitute->id])}}" class="list-group-item">
					<h3 class="list-group-item-heading">請代課老師 <small>{{$substitute->from}} 至 {{$substitute->to}}</small></h3>
	    			<p class="list-group-item-text">代課老師 ─ <mark>{{$substitute->substitute_teacher}}</mark></p>
				</a>  
  			</ul>  				
		</div>
	@endforeach

	@foreach($noCurriculums as $noCurriculum)
		<div class="panel panel-default panel-no-curriculum">
			<ul class="list-group">
				<a href="#" class="list-group-item">
					<h3 class="list-group-item-heading">無課務</h3>
					<p class="list-group-item-text">請假時間 ─ <small> {{$noCurriculum->from}} 至 {{$noCurriculum->to}} </small></p> 
				</a>
			</ul>
		</div>
	@endforeach
	
@stop
