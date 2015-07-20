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

    {!! Form::model($switching = new App\ClassSwitching(['user_id' => Auth::user()->id]), ['action'=>['ClassSwitchingsController@store'], 'class'=> 'form-horizontal']) !!}
        
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
<script type="text/javascript">
$(document).ready(function() {
    var count = 0;   

    $('.panel-body').on('click', 'button.close', function() {       
        if (count === 0) { return; }        
        $(this).parents('.classSwitchingForm').remove();
        count--;
    });

    $('button[type=submit]').click(function(event) {        

        $('.class_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        $('.teacher_list').each(function(index, element) {
            $(this).select2('destroy');
        });

        $('.period').select2('destroy');
       
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

        $('.period').each(function(index, element) {
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
            minimumInputLength: 1
        });

        $('.period').select2({
            minimumResultsForSearch: Infinity
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
        language: { 
            inputTooShort: function () {
                return "最少須輸入 1 個字";
            }
        },
        minimumInputLength: 1
    });

    $(".period").select2({
        minimumResultsForSearch: Infinity
    });

});
</script>

@stop
