@extends('app')

@section('content')
		<div class="page-header">
  			<h1>匯出日誌檔</h1>  			
		</div>

		<div id="export-deletion-log" class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">匯出刪除日誌</h3>
      </div>
      <div class="panel-body">
        {!! Form::open(['url'=>'manager/export/userDeletionLog', 'class' => 'form-inline']) !!}
          
          <div class="form-group">
            <span class="h3">輸出</span>
            {!! Form::input('date', 'start', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}        
          </div>


          <div class="form-group">
            <span class="h3">到</span> 
            {!! Form::input('date', 'end', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}        
          </div>
        
        <span class="h3">的</span>
        <div class="form-group">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">什麼</button>
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>

            <ul class="dropdown-menu" role="menu">
              <li><a class="export-log" href="export/leaveDeletionLog">被刪除的請假</a></li>
              <li><a class="export-log" href="export/userDeletionLog">被刪除的帳號</a></li>
            </ul>
        </div>  

        <span class="h3">的日誌檔。</span>      

  {!! Form::close() !!} 
      </div>
    </div>

		
@stop

@section('footer')
<script src="/js/jquery.blockUI.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('a.export-log').click(function(e) {
      e.preventDefault();
      console.log($(this).attr('href'));
      var startDate = $('input[name=start]').val();
      var endDate = $('input[name=end]').val();

      $('#export-deletion-log').block(
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
            start: startDate,
            end: endDate
          },          
          success: generateDownloadLink($(this).attr('href'), startDate, endDate),
          error: function(xhr, textStatus) {
            console.log(xhr.responseText);            
          }
      });

    });
	
});

function generateDownloadLink(url, startDate, endDate) {
  return function(data){
    window.location = url + '?start=' + startDate + '&end=' + endDate;
    $('#export-deletion-log').unblock();
  };
}
</script>
@stop
