@extends('app')

@section('content')
	<h1>節次列表</h1>
		
	<hr>
	<p class="text-danger collapse">輸入有誤，請確認輸入內容 (例：不可空白)	</p>
	<p class="text-success collapse">節次新增成功！</p>
	<div class="row">
  	  <div class="col-md-3">
  	    <input id="period" class="form-control" placeholder="輸入節次名稱">
  	  </div>
  	  <div class="col-md-3"><a id="add-period" class="btn btn-primary" role="button">新增</a></div>
	</div>

	<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="/api/periods/search">
    <thead>
        <tr>            
            <th data-column-id="name">節次</th>    
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
        </tr>
    </thead>
	</table>
@stop

@section('footer')
	<script>
	$(document).ready(function() {

		$('#add-period').on('click', function() {
			$.ajax({
				method: 'POST',
  				url: '/periods',
  				data: {
  					_token: "{{ csrf_token() }}",
  					period: $('#period').val()
  				},
  				dataType: 'text',
  				beforeSend:function() {
  					$('p.collapse').hide();
  					var period = $('#period').val().trim();
  					if(period === '') {
  						$('p.text-danger').fadeIn(150);
  						return false;
  					}
  				},
  				success: function(data) {
  					$('p.text-success').fadeIn(150);
  					$('#grid-basic').bootgrid('reload');
  				},
  				error: function(xhr, textStatus) {
  					$('p.text-danger').fadeIn(150);
  					console.log(xhr.responseText);
  				}
			});
		});
		
		$('#grid-basic').bootgrid({
			ajaxSettings: {
        		method: 'GET'
    		},	
			labels: {
				refresh: '重新載入',
				loading: '載入中...',
				search: '搜尋節次',
				noResults: '無搜尋結果！',
				infos: '第 @{{ctx.start}} 筆 至 第 @{{ctx.end}} 筆，共 @{{ctx.total}} 筆'
			},		  	
			formatters: {				
				commands: function(column, row) {
					return '<button type="button" class="btn btn-default btn-sm command-edit" data-row-id="' + row.id + '"><span class="glyphicon glyphicon-pencil"></span></button> ' + 
					'<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
				}
			}
		}).on('loaded.rs.jquery.bootgrid', function(e) {

			$('.command-edit').on('click', function() {
				var oldPeriod = $(this).parent().siblings('td').text();				
				$('#modal-edit-form').data('target-id', $(this).data('row-id'));
				$('#modal-edit-form .form-group p').text(oldPeriod);
				$('#modal-edit-form .form-group input').val('');
				$('#modal-edit-form').modal('show');
				
			});		

			$('.command-delete').on('click', function() {
				$('#myModal').data('target-id', $(this).data('row-id'));
				$('#myModal').modal('show');				
			});
		});

		// Modal for deleting periods.
		$('#myModal .modal-footer .confirm').on('click', function() {
			
			$.ajax({
				method: 'DELETE',
  				url: '/periods/' + $('#myModal').data('target-id'),
  				data: {
  					_token: "{{ csrf_token() }}"
  				},
  				dataType: 'text',
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

		//Modal for updating periods.
		$('#modal-edit-form .modal-footer .confirm').on('click', function() {
			
			$.ajax({
				method: 'PATCH',
  				url: '/periods/' + $('#modal-edit-form').data('target-id'),
  				data: {
  					_token: "{{ csrf_token() }}",  					
  					newPeriod: $('#modal-edit-form input').val().trim()
  				},
  				dataType: 'text',
  				success: function(data) {
  					$('#modal-edit-form').modal('hide');
  					$('#modal-edit-form').data('target-id', '-1');
  					$('#grid-basic').bootgrid('reload');
  				},
  				error: function(xhr, textStatus) {
  					$('#modal-edit-form .modal-body p.text-danger').fadeIn(150);
  					console.log(xhr.responseText);
  				}
			});
		});

		$('#modal-edit-form').on('hidden.bs.modal', function() {
			$('#modal-edit-form .modal-body p.text-danger').hide();
		});
	});
	</script>
	@include('partials.modal', 
		['message' => '你確定要刪除這個節次？ (若是目前有老師調這節課，可能會造成無法預期的結果)'])
	@include('partials.modal-form', 
		['title' => '修改節次名稱'])
@stop