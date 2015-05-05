@extends('app')

@section('content')
		<h1>請假與課務狀況</h1>
		<a class="btn btn-default btn-lg" href="/leaves/create">
  			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> 新增
		</a>
	<hr>

	@unless($isAllSwitchingPass)
		<div class="alert alert-warning" role="alert">
			<a href="leaves/unchecked_switching">有新的調課尚未確認</a>
		</div>
	@endunless
	
	@foreach($passedSwitchings as $switching)
		<div class="panel panel-default panel-switching-class">
	  		<ul class="list-group">	  			
	    		<a href="{{ action('LeavesController@switching', [$switching->id]) }}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課</h3>
	    			<p class="list-group-item-text">{{$switching->from}}</p>
	    		</a>  
	  		</ul>
		</div>			
	@endforeach
	
	@foreach($passedSwitchingsFromOthers as $switching)
		<div class="panel panel-default panel-switching-class-others">			
	  		<ul class="list-group">
	    		<a href="{{ action('LeavesController@switching', [$switching->id]) }}" class="list-group-item">
	    			<h3 class="list-group-item-heading">調課</h3>
	    			<p class="list-group-item-text">{{$switching->to}}</p>
	    		</a>
	  		</ul>
		</div>			
	@endforeach

	@foreach($substitutes as $substitute)
			<div class="panel panel-default panel-substitute">
  				<ul class="list-group">
    				<a href="{{ action('LeavesController@substitute', [$substitute->id]) }}" class="list-group-item">
						<h3 class="list-group-item-heading">請代課老師</h3>
	    				<p class="list-group-item-text">{{$substitute->from}} 至 {{$substitute->to}}</p>
					</a>  
  				</ul>  				
			</div>
	@endforeach		
@stop