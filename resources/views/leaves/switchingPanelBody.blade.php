<div class="row">
    <div class="col-md-6">
        @include('leaves.switchingUnitPanel', 
                 [ 'title' => '調課申請老師', 
                   'name' => $switching->switchingTeacher->name, 
                   'date'=> $switching->from, 
                   'period' => $switching->fromPeriod->name, 
                   'class' => $switching->fromClass->title])
    </div>
    <div class="col-md-6">
        @include('leaves.switchingUnitPanel', 
                 [ 'title' => '被調課老師', 
                   'name' => $switching->withSwitchingTeacher->name, 
                   'date'=> $switching->to, 
                   'period' => $switching->toPeriod->name, 
                   'class' => $switching->toClass->title])
    </div>
</div>
