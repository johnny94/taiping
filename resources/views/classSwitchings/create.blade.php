@extends('app')

@section('content')
    <h1>建立調課</h1>   
    <hr>

    {!! Form::model($switching = new App\ClassSwitching(['user_id' => Auth::user()->id]), ['action'=>['ClassSwitchingsController@store'], 'class'=> 'form-horizontal']) !!}
        
        <div class="panel panel-default classSwitchingForm">   
            <div class="panel-body">
            @include('leaves.switching_form')
            </div>
        </div>
    
    {!! Form::button('新增', ['id' => 'addClassSwitchingForm', 'class'=>'btn btn-success']) !!}
    {!! Form::submit('完成', ['class'=>'btn btn-primary']) !!}

    {!! Form::close() !!}
  
@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {
    var count = 0;

    $('#addClassSwitchingForm').click(function() {

        $('.class_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        $('.teacher_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        var $cloneForm = $('.classSwitchingForm').first().clone();
        count++;
        $cloneForm.find('.form-control').attr('name', function(i, val) {
            return val.replace(/(classSwitching)\[\d+\]\[(\w+)\]/g, '$1[' + count + '][$2]');
        });

        $cloneForm.insertBefore('#addClassSwitchingForm');

        $('.class_list').select2();

        $(".teacher_list").select2({
            ajax: {
                url: "/teachers",
                dataType: "json",
                delay: 250,
                processResults: function(data, page) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
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

    $('.class_list').select2();

    $(".teacher_list").select2({
        placeholder:'請輸入老師姓名',
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
