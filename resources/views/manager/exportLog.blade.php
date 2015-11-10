@extends('app')

@section('content')
		<div class="page-header">
  			<h1>匯出日誌檔</h1>
		</div>
    <div class="panel panel-default export-deletion-log">
      <div class="panel-heading">
        <h3 class="panel-title">調課</h3>
      </div>

      <div class="panel-body">
        {!! Form::open(['class' => 'form-inline']) !!}

          <div class="form-group">
            <span class="h3">匯出</span>
            {!! Form::input('date', 'start', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          </div>


          <div class="form-group">
            <span class="h3">到</span>
            {!! Form::input('date', 'end', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          </div>

          <span class="h3">所有調課被刪除的紀錄。</span>
          <a id="switching-deletion-log" class="btn btn-primary">確定</a>
          <p class="text-info">日期請輸入調課的日期，而非調課被刪除的日期</p>
        {!! Form::close() !!}
      </div>
    </div>


      <div class="panel panel-default export-deletion-log">
      <div class="panel-heading">
        <h3 class="panel-title">帳號</h3>
      </div>
      <div class="panel-body">
        <span class="h3">匯出所有帳號被刪除的紀錄。</span>
        <button id="user-deletion-log" class="btn btn-primary">確定</button>
      </div>
    </div>

@stop

@section('footer')
<script src="/js/jquery.blockUI.js"></script>
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(function() {

    $('#switching-deletion-log').on('click', function(e) {
      //e.preventdefault();
      // TODO: Unable to unblock panel after the download pop-up appeard.
      /*$(this).parents('div.panel').block(
        { message: '輸出中...',
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
      }});*/
      var queryParam = {
            start: $('input[name=start]').val(),
            end: $('input[name=end]').val()
      };
      TAIPING.downloadRequest.init('/logs/download/switching-deletion-log', queryParam);
    });


    $('#user-deletion-log').click(function(e) {

      // TODO: Unable to unblock panel after the download pop-up appeard.
      /*$(this).parents('div.panel').block(
        { message: '輸出中...',
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }});*/
      TAIPING.downloadRequest.init('/logs/download/user-deletion-log');
    });

});
</script>
@stop
