@extends('app')

@section('content')
		<div class="page-header">
  			<h1>匯出日誌檔</h1>  			
		</div>

		

      <div class="panel panel-default export-deletion-log">
      <div class="panel-heading">
        <h3 class="panel-title">匯出帳號日誌</h3>
      </div>
      <div class="panel-body">
        <span class="h3">匯出所有被刪除帳號的日誌檔。</span>
        <a class="btn btn-primary export-log" href="export/userDeletionLog">確定</a>

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
</script>
@stop
