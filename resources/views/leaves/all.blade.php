@extends('app')

@section('content')
	<h1>全校請假名單</h1>
	<hr>
	<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="leaves/all">
    <thead>
        <tr>            
            <th data-column-id="name">老師</th>
            <th data-column-id="from" data-order="desc">請假時間 (起)</th>
            <th data-column-id="to">請假時間 (訖)</th>
            <th data-column-id="leavetype" data-formatter="leavetype">假別</th>
            <th data-column-id="curriculum" data-formatter="curriculum">課務處裡</th>
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">Commands</th>
        </tr>
    </thead>
	</table>	

@stop

@section('footer')
	<script type="text/javascript">
	$(document).ready(function() {
		
		$('#grid-basic').bootgrid({
			labels: {
				refresh: '重新載入',
				loading: '載入中...',
				search: '搜尋老師',
				noResults: '無搜尋結果！',
				infos: '第 @{{ctx.start}} 筆 至 第 @{{ctx.end}} 筆，共 @{{ctx.total}} 筆'
			},
		  	post: function() {
				return {
					_token: "{{ csrf_token() }}"
				};
			},
			formatters: {
				leavetype: function(column, row) {
					return '<button type="button" class="btn btn-info btn-sm" data-container="body" data-toggle="popover" data-placement="left" title="事由" data-content="' + row.reason + '"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> ' + row.leavetype + '</button>';

				},
				curriculum: function(column, row) {
					if(row.curriculum !== '無課務'){
						return '<a href="' + '/leaves/' + row.leaveID + '/curriculums">'+ row.curriculum +'</a>';
					}
					
					return row.curriculum;
				},
				commands: function(column, row) {
					return '<a class="btn btn-default" href="/leaves/' + row.leaveID + '"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 刪</a>';
				}
			}
		}).on('loaded.rs.jquery.bootgrid', function(e){
			$('[data-toggle="popover"]').popover();
		});

		
	});
	</script>
@stop