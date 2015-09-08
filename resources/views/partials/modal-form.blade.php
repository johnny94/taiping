<div class="modal fade" id="modal-edit-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-target-id="-1">
	<div class="modal-dialog">
   		<div class="modal-content">
     			<div class="modal-header">
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       			<h4 class="modal-title" id="myModalLabel">{{ $title }}</h4>
     			</div>
      		<div class="modal-body">             
        	   <form class="form-horizontal">
              <div class="form-group">
                <label class="col-md-2 control-label">原名稱</label>
                <div class="col-md-10">
                  <p class="form-control-static"></p>
                </div>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-sm-2 control-label">新名稱</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" placeholder="輸入新的科目名稱">
                </div>
              </div>              
             </form>
             <p class="text-danger pull-right collapse">輸入有誤，請確認輸入內容 (例：不可空白)</p>
             <br>
      		</div>
      		<div class="modal-footer">
				    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
        		<button type="button" class="btn btn-primary confirm">修改</button>
      		</div>
   		</div>
		</div>
</div>