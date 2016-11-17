<div class="navbar-header">
<!-- Collapsed Hamburger -->
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
     <span class="sr-only">Toggle Navigation</span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
    </button>

    <!-- Branding Image -->
    <a class="navbar-brand" href="{{ url('/') }}">
    CG 遊戲數據後台
    </a>
</div>

<div class="collapse navbar-collapse" id="app-navbar-collapse">
<!-- Right Side Of Navbar -->
    <ul class="nav navbar-nav navbar-right">
    <!-- Authentication Links -->
    @if (Auth::check())
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;
            {{ Auth::user()->name }} 您好 
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="{{ url('password/own') }}">
                        <i class="fa fa-refresh"></i>  修改密碼
                    </a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}">
                        <i class="fa fa-btn fa-sign-out"></i>登出
                    </a>
                </li>
             </ul>
        </li>
    @endif
    </ul>
</div>