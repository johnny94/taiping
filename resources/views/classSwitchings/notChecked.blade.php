@extends('main')

@section('title')
	<div class="page-header">
        <h1>請假與課務狀況 <small>確認調課</small></h1>
        <a class="btn btn-default" href="/leaves/create">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增請假
    </a>
    </div>
@stop

@section('content')

  @if ($rejectedSwitchings->isEmpty() && $pendingSwitchings->isEmpty() && $pendingSwitchingsFromOthers->isEmpty())
    <div class="panel panel-default">
      <div class="panel-body">
        沒有任何調課
      </div>
    </div>    
  @endif

  @foreach($rejectedSwitchings as $switching)
    @include('leaves.switchingPanel', ['switching' => $switching, 'title'=> '老師認為調課資訊有誤', 'context' =>'panel-danger'])
  @endforeach

  @foreach($pendingSwitchings as $switching)
    @include('leaves.switchingPanel', ['switching' => $switching, 'title' => '老師尚未確認你的調課請求'])
  @endforeach

  @foreach($pendingSwitchingsFromOthers as $switching)
    @include('leaves.switchingFromOthersPanel', ['switching' => $switching])
  @endforeach
  

@stop