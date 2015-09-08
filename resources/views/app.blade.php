<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Taiping</title>
   
    <link rel="stylesheet" type="text/css" href="/css/all.css">
    <style type="text/css">
        .panel-switching-class {
            border-left-color: #337ab7;
            border-left-width: 5px;
        }

        .panel-switching-class-others {
            border-left-color: #ee827c;
            border-left-width: 5px;
        }
       
        /* Can reuse. */
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
        @yield('content')
    </div>    
   
    <script src="/js/all.js"></script>
    @yield('footer')

</body>

</html>
