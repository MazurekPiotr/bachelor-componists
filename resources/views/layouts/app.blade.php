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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Scripts -->
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
        <nav id="header" class="navbar navbar-fixed-top">
            <div id="header-container" class="container navbar-container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a id="brand" class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbar">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="{{ route('componists.projects.index') }}">All Topics</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li>
                                    @if (Auth::user()->role === 'admin')
                                        <li><a href="{{ route('admin.dashboard.index') }}">Admin Dashboard</a></li>
                                    @endif
                                    @if (Auth::user()->isElevated())
                                        <li><a href="{{ route('moderator.dashboard.index') }}">Moderator Dashboard</a></li>
                                    @endif
                                    <li><a href="{{ route('home.index') }}">My Topics</a></li>
                                    <li><a href="{{ route('user.chat.threads.index') }}"><span>My Messages {!! Auth::user()->hasUnreadMessages() ? '<span class="badge">' . Auth::user()->unreadMessageCount(). '</span>' : '' !!}</span></a></li>
                                    <li><a href="{{ route('user.profile.index', Auth::user()->name) }}">{{ Auth::user()->name }}'s profile</a></li>
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    @if( Route::getCurrentRoute()->getPath() == 'projects/{project}')
        <script src="/js/waveform.js"></script>
        <script src="/js/multitrack.js"></script>
        <script src="/js/emitter.js"></script>
        <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
        <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
        <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
        <script src="/js/responsive.min.js" type="text/javascript"></script>
        <script src="/js/chart.js"></script>
    @endif
    @if( Route::getCurrentRoute()->getPath() == 'projects')
        <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
        <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
        <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
        <script src="/js/responsive.min.js" type="text/javascript"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
        <script src="/js/chart.js"></script>
    @endif
    <script>
        $(document).ready(function(){

            var myNavBar = {

                flagAdd: true,

                elements: [],

                init: function (elements) {
                    this.elements = elements;
                },

                add : function() {
                    if(this.flagAdd) {
                        for(var i=0; i < this.elements.length; i++) {
                            document.getElementById(this.elements[i]).className += " fixed-theme";
                        }
                        this.flagAdd = false;
                    }
                },

                remove: function() {
                    for(var i=0; i < this.elements.length; i++) {
                        document.getElementById(this.elements[i]).className =
                            document.getElementById(this.elements[i]).className.replace( /(?:^|\s)fixed-theme(?!\S)/g , '' );
                    }
                    this.flagAdd = true;
                }

            };

            /**
             * Init the object. Pass the object the array of elements
             * that we want to change when the scroll goes down
             */
            myNavBar.init(  [
                "header",
                "header-container",
                "brand"
            ]);

            /**
             * Function that manage the direction
             * of the scroll
             */
            function offSetManager(){

                var yOffset = 0;
                var currYOffSet = window.pageYOffset;

                if(yOffset < currYOffSet) {
                    myNavBar.add();
                }
                else if(currYOffSet == yOffset){
                    myNavBar.remove();
                }

            }

            /**
             * bind to the document scroll detection
             */
            window.onscroll = function(e) {
                offSetManager();
            }

            /**
             * We have to do a first detectation of offset because the page
             * could be load with scroll down set.
             */
            offSetManager();
        });
    </script>
</body>
</html>
