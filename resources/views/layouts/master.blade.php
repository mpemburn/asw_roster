<html>
<head>
    <link href="{{ URL::to('/') }}/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
    <title>@yield('title')</title>
</head>
<body>
<div class="container">
    <div class="container-fluid">
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="#">Home</a></li>
                <li class="dropdown {{ Request::is('member') ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Members <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/public/member">Members List</a></li>
                        <li><a href="/public/member/details">Add Member</a></li>
                        <li><a href="#">My Profile</a></li>
                    </ul>
                </li>
                <li class="{{ Request::is('faq') ? 'active' : '' }}">
                    <a href="{{ url('faq') }}">FAQ</a>
                </li>
                <li class="{{ Request::is('auth/login') ? 'active' : '' }}">
                    <a href="{{ url('auth/login') }}">Login</a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
    @yield('content')
</div>
</body>
</html>