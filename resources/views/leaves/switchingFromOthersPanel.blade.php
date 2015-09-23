<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
          {{ $switching->switchingTeacher->name }} 老師與你調課          
        
          {!! Form::open(['method' => 'PATCH', 'action'=>['ClassSwitchingsController@updateStatus', $switching->id], 'style'=>'display: inline']) !!}

            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="退回調課單並請調課老師修改" type="submit">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> 有問題
            </button>
            {!! Form::hidden('status', 'reject') !!}

          {!! Form::close() !!}

          {!! Form::open(['method' => 'PATCH', 'action'=>['ClassSwitchingsController@updateStatus', $switching->id], 'style'=>'display: inline']) !!}

            <button class="btn btn-success btn-sm" type="submit">
              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 確認
            </button>
            {!! Form::hidden('status', 'pass') !!}

          {!! Form::close() !!}
        </h3>
    </div>
    <div class="panel-body">
        @include('leaves.switchingPanelBody', ['switching', $switching])
    </div>
</div>
