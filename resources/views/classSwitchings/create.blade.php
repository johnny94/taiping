@extends('app')

@section('content')
    <h1>建立調課</h1>   
    <hr>

    {!! Form::model($switching = new App\ClassSwitching(['user_id' => Auth::user()->id]), ['action'=>['ClassSwitchingsController@store'], 'class'=> 'form-horizontal']) !!}
        
        <div class="panel panel-default classSwitchingForm">   
            <div class="panel-body">                
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="panel-heading">
                </div>   
            @include('leaves.switching_form')
            </div>
        </div>

    <a id="back" class="btn btn-primary" href="{{ action('LeavesController@create') }}" role="button">上一步</a>
    {!! Form::button('新增一節', ['id' => 'addClassSwitchingForm', 'class'=>'btn btn-success']) !!}
    {!! Form::submit('完成', ['class'=>'btn btn-primary']) !!}

    {!! Form::close() !!}
  
@stop

@section('footer')
<script type="text/javascript">
$(document).ready(function() {
    var count = 0;   

    $('.panel-body').on('click', 'button.close', function() {       
        if (count === 0) { return; }        
        $(this).parents('.classSwitchingForm').remove();
        count--;
    });

    $('input[type=submit]').click(function(event){

        $('.class_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        $('.teacher_list').each(function(index, element) {
            $(this).select2('destroy');
        });
       
        $('.classSwitchingForm').each(function(index, val) {
            $(val).find('.form-control').attr('name', function(i, val) {
                return val.replace(/(classSwitching)\[\d+\]\[(\w+)\]/g, '$1[' + index + '][$2]');
            });
        });        
        
    });

    $('#addClassSwitchingForm').click(function() {

        $('.class_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        $('.teacher_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        var $cloneForm = $('.classSwitchingForm').first().clone(true);
        count++;

        $cloneForm.insertBefore('#back');

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
