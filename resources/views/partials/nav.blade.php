<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">            
            <a class="navbar-brand" href="/classes">太平國小</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">            
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">{{ null !== Auth::user() ? Auth::user()->name : '' }}</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>
