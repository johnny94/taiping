@extends('app')

@section('content')
    <h1>請假</h1>		
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

  {!! Form::open(['url'=>'leaves', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        {!! Form::label('leaveType', '假別', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-8">
        {!! Form::select('leaveType', $leaveTypes, null, ['class'=>'form-control']) !!}        
        </div>        
    </div>

    <div class="form-group">
        {!! Form::label('reason', '事由', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-8">
        {!! Form::textarea('reason', null, ['class'=>'form-control', 'rows' => 3, 'placeholder' => '請輸入請假事由']) !!}        
        </div>        
    </div>



        <div class="form-group form-inline">
          {!! Form::label(null, '起', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
          {!! Form::input('date', 'from_date', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          {!! Form::input('time', 'from_time', Carbon\Carbon::now()->format('H:i'), 
          ['class'=>'form-control', 'step' => 60])!!}            
          </div>         
        </div>

        <div class="form-group form-inline">
          {!! Form::label(null, '訖', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
          {!! Form::input('date', 'to_date', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          {!! Form::input('time', 'to_time', Carbon\Carbon::now()->format('H:i'), 
          ['class'=>'form-control', 'step' => 60]) !!}  
          </div>         
        </div>


    <div class="form-group">
      {!! Form::label('curriculum', '課務處理', ['class' => 'col-sm-2 control-label']) !!}
      <div class="col-sm-8">
      {!! Form::select('curriculum', $curriculum, null, ['class'=>'form-control']) !!}
      </div>
    </div>   

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
          <button class="btn btn-primary" type="submit">
            下一步 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> 
          </button>
      </div>     
    </div>  

  {!! Form::close() !!}

@stop