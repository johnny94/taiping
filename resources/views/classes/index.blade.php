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
	
	@foreach($leaves as $leave)
		{!! $leave->renderCurriculum() !!}		
	@endforeach
	
	@unless($isAllSwitchingPass)
		<div class="alert alert-warning" role="alert">
			<a href="{{ action('ClassSwitchingsController@notChecked') }}">有新的調課尚未確認</a>
		</div>
	@endunless

@stop
