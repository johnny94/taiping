@extends('app')

@section('content')
    <h1>代課老師</h1>   
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
    {!! Form::open(['url' => 'substitutes', 'class' => 'form-horizontal']) !!}
        <div class="form-group">
          {!! Form::label('substitute_teacher', '代課老師', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
            {!! Form::text('substitute_teacher', null, ['class'=>'form-control', 'placeholder' => '請輸入代課老師姓名']) !!}
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('duration_type', '代課類型', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
            <label class="radio-inline">
              {!! Form::input('radio', 'duration_type', 'half_day', ['checked']) !!} 半天
            </label>
            <label class="radio-inline">
              {!! Form::input('radio', 'duration_type', 'full_day') !!} 全天
            </label>
            <label class="radio-inline">
              {!! Form::input('radio', 'duration_type', 'period') !!} 節次
            </label>
          </div>
        </div>
      
      <div id="half_day">
        <div class="form-group">
          {!! Form::label('half_day[date]', '日期', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
            {!! Form::input('date', 'half_day[date]', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          </div>
        </div>
        
        <div class="form-group">
          {!! Form::label('am_pm', '期間', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
            <label class="radio-inline">
              {!! Form::input('radio', 'half_day[am_pm]', 'am', ['checked']) !!} 上午
            </label>
            <label class="radio-inline">
              {!! Form::input('radio', 'half_day[am_pm]', 'pm') !!} 下午
            </label>
          </div>        
        </div>
       
      </div>

      <div id="full_day">        
          <div class="form-group">
            {!! Form::label('full_day[date]', '起', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
              {!! Form::input('date', 'full_day[from_date]', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('full_day[date]', '訖', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
              {!! Form::input('date', 'full_day[to_date]', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
            </div>
          </div>
      </div>

      <div id="period">
        <div class="form-group">
          {!! Form::label('period[date]', '日期', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
          {!! Form::input('date', 'period[date]', Carbon\Carbon::now()->toDateString(), ['class'=>'form-control']) !!}
          </div>
        </div>

        <div class="form-group">
          {!! Form::label('period[periods][]', '節次 (按住 ctrl 多選)', ['class' => 'col-sm-2 control-label']) !!}
          <div class="col-sm-8">
          {!! Form::select('period[periods][]', $periods, [1], ['class'=>'form-control', 'multiple']) !!}
          </div>
        </div>        
      </div>

      
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <a class="btn btn-primary" href="{{ action('LeavesController@create') }}" role="button">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> 上一步
          </a>
          
          <button class="btn btn-primary" type="submit">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 完成
          </button>
        </div>       
      </div>

    {!! Form::close() !!}

@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {
  $('input[name=duration_type]').on('click', function(){
    var id = '#' + $(this).attr('value');
    $('#half_day, #full_day, #period').hide();
    $(id).show();
  });

  // Only show elements in div#half_day
  $('input[value=half_day]').trigger('click');

});
  
</script>
@stop