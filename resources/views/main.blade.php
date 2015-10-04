<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>高市太平調課系統</title>

    <link rel="stylesheet" type="text/css" href="/css/all.css">
    <style type="text/css">
        .panel-switching-class {
            border-left-color: #d9a62e;
            border-left-width: 5px;
        }

        .panel-substitute{
            border-left-color: #884898;
            border-left-width: 5px;
        }

        .panel-switching-class-others {
            border-left-color: #ee827c;
            border-left-width: 5px;
        }

        .panel-no-curriculum {
            border-left-color: #00a381;
            border-left-width: 5px;
        }

        .panel-leave {
            border-left-color: #96514d;
            border-left-width: 5px;            
        }

        .leave-description dt, .leave-description dd {
            margin-left: -100px;
            margin-right: 10px;           
            padding-right: 4px;
        }

        .badge {
            background-color: #c9171e;
        }       
                
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, "文泉驛正黑", "WenQuanYi Zen Hei", "儷黑 Pro", "LiHei Pro", Meiryo, "Meiryo UI", "微軟正黑體", "Microsoft JhengHei", "標楷體", DFKai-SB, sans-serif;
        }

        p.form-control-static {
            min-height: 0px;
            height: 28px;
        }
    </style>

</head>

<body>
    @include('partials.nav')

    <div class="container">
        @include('flash::message')
        @yield('title')
        <div class="row">
            <div class="col-md-3">
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation" {{ Request::is('classSwitchings/notChecked') ? 'class=active' : '' }}>
                    <a href="{{ action('ClassSwitchingsController@notChecked') }}"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> 未確認調課
                    @if(Auth::user()->numberOfUncheckedClassSwitching() !== 0)
                        <span class="badge">{{ Auth::user()->numberOfUncheckedClassSwitching() }}</span>
                    @endif
                    </a>
                    </li>
                    <li role="presentation" {{ Request::is('classes') ? 'class=active' : '' }}><a href="/classes"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 調課列表</a></li>
                </ul>
            </div>

            <div class="col-md-9">
                @yield('content')
            </div>      
        </div>
        
    </div>    
    
    <script src="/js/all.js"></script>
    @yield('footer')

</body>

</html>
