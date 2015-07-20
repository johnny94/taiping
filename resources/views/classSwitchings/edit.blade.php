@extends('app')

@section('content')
	<h1>修改調課</h1>   
    <hr>
    {!! Form::model($switching, ['method' => 'PATCH', 
                                 'action'=>['ClassSwitchingsController@update', $switching->id],
                                 'class'=> 'form-horizontal']) !!}

        <div class="panel panel-default">   
            <div class="panel-body">
                @include('classSwitchings.form')
            </div>
        </div>


    {!! Form::submit('完成', ['class'=>'btn btn-primary']) !!}

    {!! Form::close() !!}
@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {
    $('.class_list').select2();

    $(".teacher_list").select2({        
        ajax: {
            url: "/teachers",
            dataType: "json",
            delay: 250,
            processResults: function(data, page) {
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
        minimumInputLength: 1
    });
});
</script>

@stop