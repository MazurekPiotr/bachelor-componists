<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC|Josefin+Slab|Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script>
        window.Componists = <?php echo json_encode([
            'auth' => Auth::check(),
            'user_id' => Auth::check() ? Auth::user()->id : -1,
            'roles' => Config::get('enums.roles')
        ]); ?>

    </script>
</head>
<body>
    <div id="app">
        <nav>
            <div class="nav-wrapper">
                <a href="#" data-activates="nav-mobile" class="button-collapse" ><i class="fa fa-bars"></i></a>

            @if(Auth::user())
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ url('/') }}" class="brand-logo center">Head Componist</a>
                    @elseif (Auth::user()->isElevated() && Auth::user()->role != 'admin')
                        <a href="{{ url('/') }}" class="brand-logo center">Senior Componist</a>
                    @else
                        <a href="{{ url('/') }}" class="brand-logo center">Componists</a>
                    @endif
                @if (Auth::user()->role === 'admin')
                    <ul class="left hide-on-med-and-down">
                        <li><a href="{{ route('user.profile.index', Auth::user()->name) }}">{{ Auth::user()->name }}'s profile</a></li>
                        <li><a href="{{ route('componists.projects.index') }}">All Projects</a></li>
                        <li>
                            <form method="post" action="/search" >
                                {{ csrf_field() }}
                                <div class="input-field">
                                    <input id="search" name="keyword" type="text">
                                    <i class="fa fa-search"></i>
                                </div>
                            </form>
                        </li>
                    </ul>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="{{ route('user.chat.threads.index') }}"><span>My messages {!! Auth::user()->hasUnreadMessages() ? '<span class="badge">' . Auth::user()->unreadMessageCount(). '</span>' : '' !!}</span></a></li>
                        <li><a href="{{ route('admin.dashboard.index') }}">Admin</a></li>
                        <li><a href="{{ route('moderator.dashboard.index') }}">Moderator Dashboard</a></li>
                        <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                @else
                    <ul class="left hide-on-med-and-down">
                        <li><a href="{{ route('componists.projects.index') }}">All Projects</a></li>
                        <li><a href="{{ route('home.index') }}">My projects</a></li>
                        <li>
                            <form method="post" action="/search" >
                                {{ csrf_field() }}
                                <div class="input-field">
                                    <input id="search" name="keyword" type="text">
                                    <i class="fa fa-search"></i>
                                </div>
                            </form>
                        </li>
                    </ul>

                    <ul class="right hide-on-med-and-down"></ul>
                    @if (Auth::user()->isElevated() && Auth::user()->role != 'admin')
                            <li><a href="{{ route('moderator.dashboard.index') }}">Moderator Dashboard</a></li>
                    @endif
                    <ul class="right hide-on-med-and-down">
                        <li><a href="{{ route('user.chat.threads.index') }}"><span>My messages {!! Auth::user()->hasUnreadMessages() ? '<span class="badge">' . Auth::user()->unreadMessageCount(). '</span>' : '' !!}</span></a></li>
                        <li><a href="{{ route('user.profile.index', Auth::user()->name) }}">{{ Auth::user()->name }}'s profile</a></li>
                        <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                </ul>
                @endif
            @else
                <a href="{{ url('/') }}" class="brand-logo center">Componists</a>
                <ul class="left hide-on-med-and-down">
                    <li><a href="{{ route('componists.projects.index') }}">All projects</a></li>
                    <li>
                        <form method="post" action="/search" >
                            {{ csrf_field() }}
                            <div class="input-field">
                                <input id="search" name="keyword" type="text">
                                <i class="fa fa-search"></i>
                            </div>
                        </form>
                    </li>
                </ul>
                <ul class="right hide-on-med-and-down">
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                </ul>
            @endif
            </div>
        </nav>
        @yield('content')
    </div>
    <div class="col s12">
        <footer>
            <p class="center-align">© Componists 2017 | <a href="#">About</a> | <a href="#">Facebook</a></p>
        </footer >
    </div>
    <ul id="nav-mobile" class="side-nav">
        <a href="{{ url('/') }}" class="brand-logo">Componists</a>
        <li>
            <form method="post" action="/search" >
                {{ csrf_field() }}
                <div class="input-field">
                    <input id="search" name="keyword" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </form>
        </li>
        <li><a href="{{ route('componists.projects.index') }}">All Projects</a></li>
        @if(Auth::user())
            <li>
            @if (Auth::user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard.index') }}">Admin Dashboard</a></li>
            @endif
            @if (Auth::user()->isElevated())
                <li><a href="{{ route('moderator.dashboard.index') }}">Moderator Dashboard</a></li>
            @endif
            <li><a href="{{ route('home.index') }}">My projects</a></li>
            <li><a href="{{ route('user.chat.threads.index') }}"><span>My messages {!! Auth::user()->hasUnreadMessages() ? '<span class="badge">' . Auth::user()->unreadMessageCount(). '</span>' : '' !!}</span></a></li>
            <li><a href="{{ route('user.profile.index', Auth::user()->name) }}">{{ Auth::user()->name }}'s profile</a></li>
            <li><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        @else
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li><a href="{{ url('/register') }}">Register</a></li>

        @endif
    </ul>


</body>
<script src="/js/app.js"></script>
<script src="/js/waveform.js"></script>
<script src="/js/multitrack.js"></script>
<script src="/js/emitter.js"></script>
<script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script src="/js/responsive.min.js" type="text/javascript"></script>
<script src="/js/chart.js"></script>
</html>
