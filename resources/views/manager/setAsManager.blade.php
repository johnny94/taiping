@extends('app')

@section('content')
		<div class="page-header">
  			<h1>設定管理者權限 </h1>  			
		</div>

		@include('flash::message')

		{!! Form::open(['url'=>'manager/setManager', 'class' => 'form-horizontal']) !!}
			
			<div class="form-group">
				{!! Form::label('teacher', '使用者', ['class'=>'col-md-2 control-label']) !!}
				<div class="col-md-6">
				{!! Form::select('teacher', [], null, ['class'=>'form-control teacher_list']) !!}
				</div>
			</div>

    		<div class="form-group">
      			<div class="col-sm-offset-2 col-sm-10">
          			<button class="btn btn-primary" type="submit">
            			設為管理者  
          			</button>
      			</div>     
    		</div>  

  {!! Form::close() !!}	
@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {

	$(".teacher_list").select2({
            ajax: {
                url: "/teachers",
                dataType: "json",
                delay: 250,
                processResults: function(data, page) {
                    // parse the results into the format expected by Select2.
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        })
                    };
                }
            },
            language: { 
  				inputTooShort: function () {
    				return "最少須輸入 1 個字";
  				}
			},
            minimumInputLength: 1,
            placeholder: "請輸入使用者的姓名"
        });
});
</script
@stop
