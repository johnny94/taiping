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
            <th data-column-id="active" data-formatter="active">認證狀態</th>
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
                active: function(column, row) {
                    var $label = $('<span class="label label-warning">未認證</span>');
                    if (row.active == 1) {
                       $label = $label.html('已認證').removeClass('label-warning').addClass('label-success');

                    }
                    return $label[0].outerHTML;
                },
				commands: function(column, row) {
                    var deleteButton = '<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>',
                        verifyButton = '<button class="btn btn-default btn-sm command-active" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> 認證</button>';

					return deleteButton + ' ' + verifyButton;
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

        $('.command-active').on('click', function() {
            var $label = $(this).parent().prev().children('span');
            $.ajax({
                url: '/users/' + $(this).data('row-id')+ '/active',
                method: 'PATCH',
                dataType: 'json',
                success: function() {
                    $label.removeClass('label-warning').addClass('label-success').html('已認證')
                        .hide().fadeIn(300);
                },
                error: function(xhr, status) {
                    alert('認證失敗');
                }
            });
        });
	});


});
</script>
@include('partials.modal',
		['message' => '你確定要刪除這位使用者？ (與其相關的所有資料會一併刪除，且無法復原)'])
@stop