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
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(function() {
    var settings = {
        ajax:{
            url: '/api/users/names'
        },
        placeholder: "請輸入老師的姓名"
    };
    TAIPING.select2.init('.teacher_list', true, settings);
    TAIPING.select2.init('.period', false, {minimumResultsForSearch: Infinity});
    TAIPING.select2.init('.class_list', false);
});
</script>

@stop