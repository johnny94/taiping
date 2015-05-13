<div class="panel {{ $context or 'panel-default' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
      {{ $switching->withSwitchingTeacher->name }} {{ $title }}
    

      {!! Form::open(['method' => 'GET', 'action'=>['ClassSwitchingsController@edit', $switching->id], 'style'=>'display: inline']) !!}

        <button class="btn btn-primary btn-sm" type="submit">
          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 修改
        </button>

      {!! Form::close() !!}                         
    </h3>
    </div>
    <div class="panel-body">
        @include('leaves.switchingPanelBody', ['switching', $switching])
    </div>
</div>
