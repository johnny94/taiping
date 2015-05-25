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
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
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
					return '<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.leaveID +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
				}
			}
		}).on('loaded.rs.jquery.bootgrid', function(e) {
			$('[data-toggle="popover"]').popover();

			$('.command-delete').on('click', function() {
				$('#myModal').attr('data-leave-id', $(this).data("row-id"));
				$('#myModal').modal('show');
				
			});

		});

		$('#myModal .modal-footer .confirm-delete').on('click', function() {
			
			$.ajax({
					method: 'DELETE',
  					url: '/leaves/' + $('#myModal').data('leave-id'),
  					data: {
  						_token: "{{ csrf_token() }}"
  					},
  					dataType: 'json',
  					success: function(data) {
  						$('#myModal').modal('hide');
  						$('#myModal').attr('data-leave-id', '-1');
  						$('#grid-basic').bootgrid('reload');
  					},
  					error: function(xhr, textStatus) {
  						alert(textStatus);
  					}
			});
		});
	});
	</script>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-leave-id="-1">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        			<h4 class="modal-title" id="myModalLabel">確定刪除？</h4>
      			</div>

	      		<div class="modal-body">
	        		你確定要刪除這筆請假紀錄？ (與請假相關的課務會一併刪除，且無法復原)
	      		</div>

	      		<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
	        		<button type="button" class="btn btn-primary confirm-delete">刪除</button>
	      		</div>
    		</div>
 		</div>
	</div>
@stop