@extends('app')

@section('content')
		<div class="page-header">
  			<h1>請假與課務狀況 <small>Subtext for header</small></h1>
  			<a class="btn btn-default" href="/leaves/create">
  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
		</a>
		</div>		
		
		
	<div class="row">
		<div class="col-md-3">
			<ul class="nav nav-pills nav-stacked">
  				<li role="presentation" class="active"><a href="#">確認調課<span class="badge">42</span></a></li>
 				<li role="presentation"><a href="#">課務列表</a></li>
 				<li role="presentation"><a href="leaves/list">請假列表</a></li>
			</ul>
		</div>

		<div class="col-md-9">
			@foreach($leaves as $leave)
				{!! $leave->renderCurriculum() !!}		
			@endforeach
		</div>		
	</div>
	

	
	@unless($isAllSwitchingPass)
		<div class="alert alert-warning" role="alert">
			<a href="{{ action('ClassSwitchingsController@notChecked') }}">有新的調課尚未確認</a>
		</div>
	@endunless

	

@stop