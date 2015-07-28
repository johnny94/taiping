@extends('main')

@section('title')
  @include('partials.title', ['title' => '調課確認'])
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

@section('footer')
<script type="text/javascript">
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  })
</script>
@stop