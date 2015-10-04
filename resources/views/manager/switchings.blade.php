@extends('app')

@section('content')
	<h1>系統調課列表</h1>
	<hr>
	<p>
	  <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample">篩選器
	  <span class="caret"></span></a>
	  <button id="switching-log" class="btn btn-default">輸出調課紀錄</button>
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
	
	<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="/api/class-switchings/search">
    <thead>
        <tr>            
            <th data-column-id="teacher">調課老師</th>
            <th data-column-id="from" data-order="desc">上課日期</th>
            <th data-column-id="from_class">科目</th>
            <th data-column-id="from_period">節次</th>
			<th data-column-id="with_teacher">被調課老師</th>
            <th data-column-id="to">上課日期</th>
            <th data-column-id="to_class">科目</th>
            <th data-column-id="to_period">節次</th>
            <th data-column-id="status" data-formatter="status">確認狀態</th>           
        	<th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
        </tr>
    </thead>
	</table>
@stop

@section('footer')
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(function() {

	var $grid = TAIPING.bootgrid.init(
			'#grid-basic',
			{
				post: function() {
					return {					
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
			}			
		),

	$modal = TAIPING.deleteResourceModal.init(
				'#myModal',
				'/manager/deleteSwitching/',
				function() { $grid.bootgrid('reload'); }
			);

	$grid.on('loaded.rs.jquery.bootgrid', function(e) {
		$('.command-delete').on('click', function() {
			$modal.show($(this).data('row-id'));			
		});
	});	

	$('#switching-log').on('click', function(e) {		
		var queryParam = {
      		filterByDate: $('input:checkbox').prop('checked'), 			
         	filterFrom: $('input[name=filter-from]').val(),
           	filterTo: $('input[name=filter-to]').val(),
           	searchPhrase: $('.search-field').val()
      	};
      	TAIPING.downloadRequest.init('/logs/download/switching-log', queryParam);
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
});

</script>

@include('partials.modal', 
	['message' => '你確定要刪除這筆調課紀錄？ (與其相關的所有資料會一併刪除，且無法復原)'])

@stop