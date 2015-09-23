<div class="row"> 
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">調課申請老師</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">申請老師</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{ $switching->switchingTeacher->name }}</p>
                    </div>                    
                </div>
                <div class="form-group">
                    {!! Form::label('from_date', '日期', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::input('date', 'classSwitching[0][from_date]', $switching->from, ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('from_period', '節次', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::select('classSwitching[0][from_period]', $periods, $switching->from_period, ['class'=>'form-control period']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('from_class', '科目', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::select('classSwitching[0][from_class]', $classes, $switching->from_class_id, ['class'=>'form-control class_list']) !!}
                    </div>
                </div>
            </div>
    </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">被調課老師</div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('teacher', '被調課老師', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        @if (isset($switching->with_user_id)) {{-- Visit the form for edithing class switching --}}
                            {!! Form::select('classSwitching[0][teacher]', [$switching->with_user_id => $switching->withSwitchingTeacher->name], $switching->with_user_id, ['class'=>'form-control teacher_list']) !!}

                        @else
                            {!! Form::select('classSwitching[0][teacher]', [], $switching->with_user_id, ['class'=>'form-control teacher_list']) !!}
                        @endif                        
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('to_date', '日期', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::input('date', 'classSwitching[0][to_date]', $switching->to, ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('to_period', '節次', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::select('classSwitching[0][to_period]', $periods, $switching->to_period, ['class'=>'form-control period']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('to_class', '科目', ['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-9">
                        {!! Form::select('classSwitching[0][to_class]', $classes, $switching->to_class_id, ['class'=>'form-control class_list']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
