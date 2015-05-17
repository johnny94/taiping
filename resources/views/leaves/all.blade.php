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
            <th data-column-id="curriculum" data-formatter="curriculum" data-sortable="false">課務處裡</th>
        </tr>
    </thead>
	</table>	

@stop

@section('footer')
	<script type="text/javascript">
	$(document).ready(function() {
		
		$('#grid-basic').bootgrid({
			labels : {
				refresh:'重新載入'
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
				curriculum :function(column, row) {
					if (row.switchingID) {
						return '<a href="' + '/classSwitchings/' + row.switchingID + '">'+ row.curriculum +'</a>';
					}

					if (row.substituteID) {
						return '<a href=\"' + '/substitutes/' + row.substituteID + '\">'+ row.curriculum +'</a>';
					}

					return row.curriculum;					
				}
			}
		}).on('loaded.rs.jquery.bootgrid', function(e){
			$('[data-toggle="popover"]').popover();
		});

		
	});
	</script>
@stop