<div class="modal fade" id="{{ $id or 'myModal' }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-target-id="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">確定刪除？</h4>
     	</div>
      <div class="modal-body"> {{ $message }} </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary confirm">刪除</button>
      </div>
   	</div>
	</div>
</div>