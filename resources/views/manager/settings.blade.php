@extends('app')

@section('content')
<div class="page-header">
    <h1>設定管理者權限 </h1>
</div>

@include('flash::message')
<div class="row">


    <div class="col-md-7">
<strong>管理人員名單</strong>
@if (count($managers) > 0)
   <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>E-Mail</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($managers as $manager)
            <tr data-row-id="{{ $manager->id }}">
                <th>{{ $manager->id }}</th>
                <td>{{ $manager->name }}</td>
                <td>{{ $manager->email }}</td>
                <td>
                    {!! Form::open(['method' => 'DELETE', 'url'=>'/users/manager/' . $manager->id]) !!}
                        <button class="btn btn-default btn-sm unset-manager" type="submit">刪除權限</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
    </div>

    <div class="col-md-5">

{!! Form::open(['method' => 'PUT', 'url'=>'/users/manager']) !!}
    <div class="form-group">
        <label class="sr-only" for="teacher">使用者</label>
        <div class="col-md-8">
        {!! Form::select('teacher', [], null, ['class'=>'form-control teacher_list']) !!}
        </div>
    </div>
    <button class="btn btn-primary" type="submit">設為管理者</button>
{!! Form::close() !!}
    </div>

</div>

@stop

@section('footer')
<script src={{ elixir("js/app.js") }}></script>
<script>
$(document).ready(function() {
    var settings = {
        ajax:{
            url: '/api/users/names'
        },
        placeholder: "請輸入使用者的姓名"
    };
    TAIPING.select2.init('.teacher_list', true, settings);

    /*$('button.unset-manager').on('click', function(event) {
        var userID = $(this).closest('tr').data('row-id');
        var $currentRow = $(this).closest('tr');

        $.ajax({
            url: '/users/manager/' + userID,
            method: 'DELETE',
            dataType: 'json',
            success: function(xhr, status) {
                $currentRow.fadeOut(300, function() { $(this).remove(); } );
            },
            error: function(xhr, status) {
                alert('刪除失敗！');
            }
        });

    });*/

});
</script>
@stop
