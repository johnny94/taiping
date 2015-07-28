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
          <a id="switching-deletion-log" class="btn btn-primary" href="export/switchingDeletionLog" role="button">確定</a>   

        {!! Form::close() !!} 
      </div>
    </div>
		

      <div class="panel panel-default export-deletion-log">
      <div class="panel-heading">
        <h3 class="panel-title">帳號</h3>
      </div>
      <div class="panel-body">
        <span class="h3">匯出所有帳號被刪除的紀錄。</span>
        <a id="user-deletion-log" class="btn btn-primary export-log" href="export/userDeletionLog">確定</a>

      </div>
    </div>
		
@stop

@section('footer')
<script src="/js/jquery.blockUI.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#switching-deletion-log').on('click', function(e) {
      e.preventDefault();
      $(this).parents('div.panel').block(
        { message: '輸出中...',
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
      }});

      var startDate = $('input[name=start]').val();
      var endDate = $('input[name=end]').val();
      $.ajax({
          method: 'GET',
          url: $(this).attr('href'),
          data: {           
            start: startDate,
            end: endDate
          },
          success: generateDownloadLinkWithDate($(this).attr('href'), startDate, endDate),
          error: function(xhr, textStatus) {
            console.log(xhr.responseText);            
          }
      });

    })

    $('#user-deletion-log').click(function(e) {
      e.preventDefault();
      console.log($(this).attr('href'));     

      $(this).parents('div.panel').block(
        { message: '輸出中...',
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        }});
   
      $.ajax({
          method: 'GET',
          url: $(this).attr('href'),
          data: {           
            start: 'startDate'            
          },          
          success: generateDownloadLink($(this).attr('href')),
          error: function(xhr, textStatus) {
            console.log(xhr.responseText);            
          }
      });

    });
	
});

function generateDownloadLink(url) {
  return function(data){
    window.location = url;
    $('div.panel').unblock();
  };
}

function generateDownloadLinkWithDate(url, start, end) {
  return function(data){
    window.location = url + '?start=' + start + '&end=' + end;
    $('div.panel').unblock();
  };
}
</script> 
@stop
