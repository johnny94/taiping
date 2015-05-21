@extends('main')

@section('title')
	<div class="page-header">
  		<h1>請假與課務狀況 <small>請假列表</small></h1>
  		<a class="btn btn-default" href="/leaves/create">
  		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
		</a>
	</div>
@stop		
		
@section('content')	
	@foreach($leaves as $leave)
		<div class="panel panel-default panel-leave">
			<div class="panel-body leave-description">
    			<h3 class="list-group-item-heading">請假</h3>
				<dl class="dl-horizontal">
  					<dt>請假時間</dt>
  					<dd>{{ $leave->from }} 至 {{ $leave->to }}</dd>
  					<dt>假別</dt>
  					<dd>{{ $leave->getLeaveType() }}</dd>
  					<dt>事由</dt>
  					<dd>{{ $leave->reason }}</dd>
  					<dt>課務處理</dt>
  					@if($leave->curriculum_id === 1) {{-- No Curriculum --}}
  						<dd><a href="#">{{ $leave->getCurriculum() }}</a></dd>
  					@elseif ($leave->curriculum_id === 2) {{-- Class Switching --}}
  						<dd>
                <a href="{{action('LeavesController@showCurriculums', $leave->id)}}">{{ $leave->getCurriculum() }} (共 {{$leave->classSwitchings->count()}} 節)</a>
                <a class="btn btn-default btn-xs" href="{{action('LeavesController@updateClassSwitchings', $leave->id)}}" data-toggle="tooltip" data-placement="top" title="在這天請假新增調課" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>新增</a>
              </dd>
  					@elseif ($leave->curriculum_id === 3) {{-- Substitute --}}
  						<dd><a href="{{action('LeavesController@showCurriculums', $leave->id)}}">{{ $leave->getCurriculum() }}</a></dd>
  					@endif  							
				</dl>
  			</div>					
		</div>
	@endforeach

@stop

@section('footer')
  <script type="text/javascript">
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();    
  });
  </script>
@stop