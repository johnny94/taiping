<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="/classes">            
            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            高雄市太平國小調課系統
            </a>
        </div>


        @if (Auth::user())
        <div id="navbar" class="collapse navbar-collapse">
            @if (Auth::user()->isManager())
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">管理者頁面 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">                    
                        <li {{ Request::is('manager/switchings') ? 'class=active' : '' }}><a href="/manager/switchings">調課列表</a></li>
                        <li {{ Request::is('manager/users') ? 'class=active' : '' }}><a href="/manager/users">使用者名單</a></li>
                        <li {{ Request::is('manager/subjects') ? 'class=active' : '' }}><a href="/manager/subjects">科目列表</a></li>
                        <li class="divider"></li>
                        <li {{ Request::is('manager/setManager') ? 'class=active' : '' }}><a href="/manager/setManager">設定管理者權限</a></li>
                        <li class="divider"></li>
                        <li {{ Request::is('manager/exportLog') ? 'class=active' : '' }}><a href="/manager/exportLog">匯出刪除紀錄</a></li>
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
