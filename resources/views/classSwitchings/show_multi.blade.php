@extends('app')

@section('content')
	<h1>調課內容</h1>		
	<hr>
	@foreach($switchings as $switching)
	<div class="panel panel-default">  	
  		<div class="panel-body">
        	@include('leaves.switchingPanelBody')
  		</div>
	</div>
	@endforeach	
@stop