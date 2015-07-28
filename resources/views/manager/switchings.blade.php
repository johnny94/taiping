@extends('app')

@section('content')
	<h1>系統調課列表</h1>
	<hr>
	<p>
	  <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample">篩選器
	  <span class="caret"></span></a>
	  <a id="switching-log" class="btn btn-default" href="export/switchingLog" role="button">輸出調課紀錄</a>
	</p>	
	<div class="collapse" id="collapseExample">
  	  <div class="well">
  	    <div class="checkbox">
		  <label>
			{!!Form::checkbox('filter-by-date') !!}
			根據日期篩選
		  </label>      
    	</div>
  	    <form class="form-inline">       
	      <div class="form-group">	      	
	          {!! Form::label('filter-from', '起', ['class'=>'control-label']) !!}	        
	          {!! Form::input('date', 'filter-from', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control', 'readonly']) !!}	        
          </div>
          <div class="form-group">
	        {!! Form::label('filter-to', '迄', ['class'=>'control-label']) !!}	        
	        {!! Form::input('date', 'filter-to', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control','readonly']) !!}	       
          </div>
        </form>
  	  </div>
	</div>
	
	<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="fetchSwitchings">
    <thead>
        <tr>            
            <th data-column-id="teacher">調課老師</th>
            <th data-column-id="from" data-order="desc">上課日期</th>
            <th data-column-id="from_class">科目</th>
            <th data-column-id="from_period">節次</th>
			<th data-column-id="with_teacher">被調課老師</th>
            <th data-column-id="to" data-order="desc">上課日期</th>
            <th data-column-id="to_class">科目</th>
            <th data-column-id="to_period">節次</th>
            <th data-column-id="status" data-formatter="status">確認狀態</th>           
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
        </tr>
    </thead>
	</table>	

@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {
	
	$('#switching-log').on('click', function(e) {
		e.preventDefault();
		
		var filterByDate = $('input:checkbox').prop('checked');
		var startDate = $('input[name=filter-from]').val();
      	var endDate = $('input[name=filter-to]').val();
      	var searchPhrase = $('.search-field').val();
		$.ajax({
       		method: 'GET',
       		url: $(this).attr('href'),
       		data: {
       			filterByDate: filterByDate, 			
         		filterFrom: startDate,
           		filterTo: endDate,
           		searchPhrase: searchPhrase
       		},
       		success: generateDownloadLinkWithDate($(this).attr('href'), startDate, endDate, filterByDate, searchPhrase),
       		error: function(xhr, textStatus) {       			
           		console.log(xhr.responseText);
       		}
   		});
	});

	var $grid = $('#grid-basic').bootgrid({			
			labels: {
				refresh: '重新載入',
				loading: '載入中...',
				search: '搜尋老師',
				noResults: '無搜尋結果！',
				infos: '第 @{{ctx.start}} 筆 至 第 @{{ctx.end}} 筆，共 @{{ctx.total}} 筆'
			},
		  	post: function() {
				return {
					_token: "{{ csrf_token() }}",
					filterByDate: $('input:checkbox').prop('checked'),
					filterFrom: $('input[name=filter-from]').val(),
					filterTo: $('input[name=filter-to]').val()
				};
			},
			formatters: {				
				status: function(column, row) {
					if(row.status === 'pending') {
						return '<span class="label label-warning">尚未確認</span>'
					} else if(row.status === 'reject') { 
						return '<span class="label label-danger">有問題</span>'
					} else if(row.status === 'pass') {
						return '<span class="label label-success">調課成功</span>'
					}

					return 'Unknown staus';
				},
				commands: function(column, row) {
					return '<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
				}

			}
	}).on('loaded.rs.jquery.bootgrid', function(e) {

			$grid.find('.command-delete').on('click', function() {
				$('#myModal').data('target-id', $(this).data('row-id'));
				$('#myModal').modal('show');				
			});

	});

	$('input:checkbox').on('change', function() {
		if($(this).prop('checked')) {
			$('input[type=date]').prop('readonly', false);
		} else {
			$('input[type=date]').prop('readonly', true);			
		}
		$grid.bootgrid('reload');	
	});

	$('input[type=date]').on('change', function() {
		if($('input:checkbox').prop('checked')) {
			$grid.bootgrid('reload');
		}			
	});

	$('#myModal .modal-footer .confirm').on('click', function() {
						
			$.ajax({
				method: 'DELETE',
  				url: '/manager/deleteSwitching/' + $('#myModal').data('target-id'),
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

function generateDownloadLinkWithDate(url, start, end, filterByDate, searchPhrase) {
  return function(data){
    window.location = url + '?filterFrom=' + start + '&filterTo=' + end + '&filterByDate=' + filterByDate + '&searchPhrase=' + searchPhrase;
  };
}
	
</script>

@include('partials.modal', 
	['message' => '你確定要刪除這筆調課紀錄？ (與其相關的所有資料會一併刪除，且無法復原)'])

@stop