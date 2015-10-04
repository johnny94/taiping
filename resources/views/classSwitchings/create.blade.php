@extends('app')

@section('content')
    <h1>新增調課</h1>   
    <hr>
    @if (count($errors) > 0)
        <div class="alert alert-danger">            
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::model($switching, ['action'=>['ClassSwitchingsController@store'], 'class'=> 'form-horizontal']) !!}
        <div class="panel panel-default classSwitchingForm">   
            <div class="panel-body">                
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="panel-heading">
                </div>   
                @include('classSwitchings.form')
            </div>
        </div>

    <a id="back" class="btn btn-primary" href="#" role="button">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> 上一步
    </a>

    <button id="addClassSwitchingForm" class="btn btn-success" type="button">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增一節
    </button>       

    <button class="btn btn-primary" type="submit">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 完成
    </button>

    {!! Form::close() !!}
  
@stop

@section('footer')
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(TAIPING.createClassSwitching.init());
</script>

@stop
