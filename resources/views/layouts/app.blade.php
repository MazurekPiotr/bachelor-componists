<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('facebook_meta')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png"  href="/assets/img/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700|Josefin+Slab|Raleway:400,400i,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="/public/css/app.css" type="text/css">
{{--     <link href="/css/app-new.css" rel="stylesheet">
 --}}
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
                                    <input id="search" name="keyword" placeholder="search" type="text">
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
                                  <input id="search" name="keyword" placeholder="search" type="text">
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
                                <input id="search" name="keyword" placeholder="search" type="text">
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
        <footer class="page-footer">
          <div class="footer-copyright">
            <div class="container">
              © Componists 2018 | <a href="/user/chat/threads/@Admin/messages">Contact</a> | <a href="/privacy-policy">Privacy policy</a> | <a href="/terms-of-use">Terms & conditions</a>

              <div class="right">
                <a><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a><i class="fa fa-instagram" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </footer >
    </div>

    <ul id="nav-mobile" class="side-nav">
      <li>
        <div class="row">
          <div class="col s12">
            <form method="post" action="/search" >
              {{ csrf_field() }}
                <div class="input-field">
                    <input id="search" name="keyword" placeholder="search" type="text" style="width:90%;">
                    <i class="fa fa-search" style="float: left;line-height: 52px;margin-right: 8px;"></i>
                </div>
            </form>
          </div>
        </div>
      </li>
        <li><a href="{{ url('/') }}">Home</a></li>
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

<script src="/public/js/app.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script src="/public/js/responsive.min.js" type="text/javascript"></script>
<script src="/public/js/chart.js"></script>

@yield('js_libs')

</html>
