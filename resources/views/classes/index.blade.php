@extends('main')

@section('title')
	@include('partials.title', ['title' => '調課列表'])
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
@stop
