@extends('app')

@section('content')
	<h1>代課老師細節</h1>		
	<hr>
  @foreach($substitutes as $substitute)
    <div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary">
          <div class="panel-heading">代課資訊</div>        
          <table class="table">
              <tbody>
              <tr>
                  <th>代課老師</th>
                  <td>{{ $substitute->substitute_teacher }}</td>
                </tr>
                <tr>
                  <th>類型</th>
                  <td>{{ $substitute->duration_type_string }}</td>
                </tr>
                <tr>
                  <th>日期</th>
                  <td>{{ $substitute->from }} 至 {{ $substitute->to }}</td>
                </tr>

                @if ($substitute->duration_type === 1) {{-- half day --}}
                <tr>
                  <th>時間</th>
                  <td>{{ $substitute->am_pm }}</td>
                </tr>
                @endif

                @if ($substitute->duration_type === 3) {{-- period --}}
                <tr>
                  <th>節次</th>
                  <td>

                  @foreach ($substitute->periods as $period)
                    <span class="label label-primary">{{ $period['name'] }}</span>                
                  @endforeach
                  
                  </td>
                </tr>                
                @endif                
                
              </tbody>              
          </table>
        </div>  
    </div>
    </div>
  @endforeach

@stop