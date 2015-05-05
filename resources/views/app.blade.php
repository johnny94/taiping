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
    </style>

</head>

<body>
   @include('partials.nav')

    <div class="container">       
        @yield('content')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.1.4/jquery.bootgrid.min.js"></script>
    @yield('footer')

</body>

</html>
