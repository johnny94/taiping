@extends('app')

@section('content')
	<h1>科目列表</h1>
	<p class="text-danger">刪除任何科目前，請確定已經刪除目前所有的調課紀錄，避免發生錯亂。</p>
	<hr>
	<div>
		<p class="text-danger collapse">輸入有誤，請確認輸入內容 (例：不可空白)   </p>
		<p class="text-success collapse">科目新增成功！</p>
	</div>
	<div class="row">
		<div class="col-md-3">
			<input id="content" class="form-control" placeholder="輸入科目名稱">
		</div>
		<div class="col-md-3"><a id="add-subject" class="btn btn-primary" role="button">新增</a></div>
		</div>

		<table id="grid-basic" class="table table-condensed table-hover table-striped" data-ajax="true" data-url="/api/subjects/search">
		<thead>
			<tr>
				<th data-column-id="title">科目</th>
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
						labels: {
										search: '搜尋科目'
						},
						post: function() {
								return {
										columnName: 'title'
								};
						},
						formatters: {
								commands: function(column, row) {
										return '<button type="button" class="btn btn-default btn-sm command-edit" data-row-id="' + row.id + '"><span class="glyphicon glyphicon-pencil"></span></button> ' +
										'<button class="btn btn-default btn-sm command-delete" data-row-id="'+ row.id +'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';
								}
						}
				}
		),

		$modal = TAIPING.deleteResourceModal.init(
				'#myModal',
				'/subjects/',
				function() { $grid.bootgrid('reload'); }
			);

	$grid.on('loaded.rs.jquery.bootgrid', function() {
		$('.command-edit').on('click', function() {
			var oldSubject = $(this).parent().siblings('td').text();
			$('#modal-edit-form').data('target-id', $(this).data('row-id'));
			$('#modal-edit-form .form-group p').text(oldSubject);
			$('#modal-edit-form .form-group input').val('');
			$('#modal-edit-form').modal('show');
		});

		$('.command-delete').on('click', function(event) {
			$modal.show($(this).data('row-id'));
		});
	});

				$('#add-subject').on('click', function() {

			TAIPING.postResourceRequest.send(
					'/subjects',
					{ subject:$('#content').val() }
				);
				$grid.bootgrid('reload');
				});

				$('#modal-edit-form .modal-footer .confirm').on('click', function() {
						$.ajax({
								method: 'PATCH',
								url: '/subjects/' + $('#modal-edit-form').data('target-id'),
								data: {
										newSubject: $('#modal-edit-form input').val().trim()
								},
								dataType: 'text',
								success: function(data) {
										$('#modal-edit-form').modal('hide');
										$('#modal-edit-form').data('target-id', '-1');
										$grid.bootgrid('reload');
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
				['message' => '你確定要刪除這個科目？ (若是有老師的調課是這個科目，可能會造成無法預期的結果)'])
		@include('partials.modal-form',
				['title' => '修改科目名稱'])
@stop