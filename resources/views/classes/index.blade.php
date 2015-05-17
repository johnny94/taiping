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

	@foreach($leaves as $leave)
		{!! $leave->renderCurriculum() !!}		
	@endforeach

@stop