@extends('app')

@section('content')
	<h1>使用者名單</h1>
	<hr>
	<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="/api/users/search">
    <thead>
        <tr>            
            <th data-column-id="name">姓名</th>
            <th data-column-id="email" data-order="desc">E-Mail</th>
            <th data-column-id="created_at">註冊時間</th>            
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
        </tr>
    </thead>
	</table>	

@stop

@section('footer')
	<script type="text/javascript">
	$(document).ready(function() {
		
		$('#grid-basic').bootgrid({
			ajaxSettings: {
        		method: 'GET'
    		},
			labels: {
				refresh: '重新載入',
				loading: '載入中...',
				search: '搜尋老師',
				noResults: '無搜尋結果！',
				infos: '第 @{{ctx.start}} 筆 至 第 @{{ctx.end}} 筆，共 @{{ctx.total}} 筆'
			},
		  	post: function() {
				return {
					//_token: "{{ csrf_token() }}"
				};
			},
			formatters: {				
				commands: function(column, row) {
					return '<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
				}
			}
		}).on('loaded.rs.jquery.bootgrid', function(e) {			

			$('.command-delete').on('click', function() {
				$('#myModal').data('target-id', $(this).data('row-id'));
				$('#myModal').modal('show');				
			});

		});

		$('#myModal .modal-footer .confirm').on('click', function() {			
			$.ajax({
				method: 'DELETE',
  				url: '/users/' + $('#myModal').data('target-id'),
  				data: {
  					_token: "{{ csrf_token() }}"
  				},
  				dataType: 'json',
  				success: function(data) {
  					$('#myModal').modal('hide');
  					$('#myModal').data('target-id', '-1');
  					$('#grid-basic').bootgrid('reload');
  				},
  				error: function(xhr, textStatus) {
  					alert(textStatus);
  					console.log(xhr.responseText);
  				}
			});
		});
	});
	</script>
	@include('partials.modal', 
		['message' => '你確定要刪除這位使用者？ (與其相關的所有資料會一併刪除，且無法復原)'])
@stop