<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Taiping</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Select2 CSS-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.1.4/jquery.bootgrid.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <style type="text/css">
        .panel-switching-class {
            border-left-color: #337ab7;
            border-left-width: 5px;
        }

        .panel-switching-class-others {
            border-left-color: #ee827c;
            border-left-width: 5px;
        }

        .panel-substitute {
            border-left-color: #d9a62e;
            border-left-width: 5px;
        }

        .panrl-no-curriculum {
            border-left-color: #00a381;
            border-left-width: 5px;
        }

        .leave-description dt, .leave-description dd {
            margin-left: -100px;
            margin-right: 10px;           
            padding-right: 4px;
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
        @yield('title')
        <div class="row">
            <div class="col-md-3">
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation" {{ Request::is('classSwitchings/notChecked') ? 'class=active' : '' }}><a href="{{ action('ClassSwitchingsController@notChecked') }}">確認調課<span class="badge">42</span></a></li>
                    <li role="presentation" {{ Request::is('classes') ? 'class=active' : '' }}><a href="/classes">課務列表</a></li>
                    <li role="presentation" {{ Request::is('leaves/list') ? 'class=active' : '' }}><a href="/leaves/list">請假列表</a></li>
                </ul>
            </div>

            <div class="col-md-9">
                @yield('content')
            </div>      
        </div>
        
    </div>    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.1.4/jquery.bootgrid.min.js"></script>
    @yield('footer')

</body>

</html>