@extends('app')

@section('content')
		<h1>請假與課務狀況</h1>
		<a class="btn btn-default" href="/leaves/create">
  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
		</a>
	<hr>

	@unless($isAllSwitchingPass)
		<div class="alert alert-warning" role="alert">
			<a href="{{ action('ClassSwitchingsController@notChecked') }}">有新的調課尚未確認</a>
		</div>
	@endunless
	
	@foreach($passedSwitchings as $switching)
		<div class="panel panel-default panel-switching-class">
	  		<ul class="list-group">	  			
	    		<a href="{{ action('ClassSwitchingsController@show', [$switching->id]) }}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課 <small>{{ $switching->from }}</small></h3>	    			
	    			<p class="list-group-item-text">調課申請人 ─ <mark>{{ $switching->switchingTeacher->name }}</mark></p>
	    		</a>  
	  		</ul>
		</div>			
	@endforeach
	
	@foreach($passedSwitchingsFromOthers as $switching)
		<div class="panel panel-default panel-switching-class-others">			
	  		<ul class="list-group">
	    		<a href="{{ action('ClassSwitchingsController@show', [$switching->id]) }}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課 <small>{{ $switching->to }}</small></h3>
	    			<p class="list-group-item-text">調課申請人 ─ <mark>{{ $switching->switchingTeacher->name }}</mark></p>
	    		</a>
	  		</ul>
		</div>			
	@endforeach

	@foreach($substitutes as $substitute)
			<div class="panel panel-default panel-substitute">
  				<ul class="list-group">
    				<a href="{{ action('SubstitutesController@show', [$substitute->id]) }}" class="list-group-item">
						<h3 class="list-group-item-heading">請代課老師 <small>{{$substitute->from}} 至 {{$substitute->to}}</small></h3>
	    				<p class="list-group-item-text">代課老師 ─ <mark>{{$substitute->substitute_teacher}}</mark></p>
					</a>  
  				</ul>  				
			</div>
	@endforeach		
@stop