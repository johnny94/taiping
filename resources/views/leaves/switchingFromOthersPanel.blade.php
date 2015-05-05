<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
          {{ $switching->switchingTeacher->name }} 老師與你調課          
        
          {!! Form::open(['method' => 'PATCH', 'action'=>['LeavesController@rejectSwitching', $switching->id], 'style'=>'display: inline']) !!}

            <button class="btn btn-danger btn-sm" type="submit">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 有問題
            </button>

          {!! Form::close() !!}

          {!! Form::open(['method' => 'PATCH', 'action'=>['LeavesController@passSwitching', $switching->id], 'style'=>'display: inline']) !!}

            <button class="btn btn-primary btn-sm" type="submit">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 確認
            </button>

          {!! Form::close() !!}
        </h3>
    </div>
    <div class="panel-body">
        @include('leaves.switchingPanelBody', ['switching', $switching])
    </div>
</div>
