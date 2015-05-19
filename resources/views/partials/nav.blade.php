<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="/classes">            
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            高雄市太平國小請假課務系統
            </a>
        </div>


        @if (Auth::user())
        <div id="navbar" class="collapse navbar-collapse">
            @if (Auth::user()->isManager())
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">管理者頁面 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li {{ Request::is('leaves') ? 'class=active' : '' }}><a href="/leaves">全校請假名單</a></li>
                    </ul>
                </li>
                
            </ul>
            @endif
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/auth/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 登出</a></li>
                    </ul>
                </li>                
            </ul>
        </div>
        @endif

    </div>
</nav>
