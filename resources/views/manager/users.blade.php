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
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(function() {
	var $grid = TAIPING.bootgrid.init(
		'#grid-basic',
		{
            post: function() {
                    return {
                        columnName: 'name'
                    };
            },
			formatters: {
				commands: function(column, row) {
					return '<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
				}
			}
		}
	),
	$modal = TAIPING.deleteResourceModal.init(
				'#myModal',
				'/users/',
				function() { $grid.bootgrid('reload'); }
			);

	$grid.on('loaded.rs.jquery.bootgrid', function() {
		$('.command-delete').on('click', function() {
			$modal.show($(this).data('row-id'));
		});
	});

});
</script>
@include('partials.modal',
		['message' => '你確定要刪除這位使用者？ (與其相關的所有資料會一併刪除，且無法復原)'])
@stop