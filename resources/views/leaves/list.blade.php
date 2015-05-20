@extends('app')

@section('content')
		<div class="page-header">
  			<h1>請假與課務狀況 <small>請假列表</small></h1>
  			<a class="btn btn-default" href="/leaves/create">
  			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
		</a>
		</div>		
		
		
	<div class="row">
		<div class="col-md-3">
			<ul class="nav nav-pills nav-stacked">
  				<li role="presentation" class="active"><a href="#">確認調課<span class="badge">42</span></a></li>
 				<li role="presentation"><a href="#">課務列表</a></li>
 				<li role="presentation"><a href="#">請假列表</a></li>
			</ul>
		</div>

		<div class="col-md-9">
			@foreach($leaves as $leave)
				<div class="panel panel-default">
					<ul class="list-group leave-description">
						<a href="#" class="list-group-item">
							<h3 class="list-group-item-heading">請假</h3>
							<dl class="dl-horizontal" >
  								<dt>請假時間</dt>
  								<dd>{{$leave->from}} 至 {{$leave->to}}</dd>
  								<dt>假別</dt>
  								<dd>{{$leave->getLeaveType()}}</dd>
							</dl>
						</a>						
					</ul>
				</div>
			@endforeach
		</div>		
	</div>

@stop