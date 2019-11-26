<nav class="navbar navbar-expand-md navbar-dark gen_nav" style="background-color: #15C5C5; color:#fff">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/brand/logo.jpg') }}" height="40" alt="logo">
            <span>Blema</span>
        </a>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
                @if(Auth::guard('admin')->check())
                    <li class="nav-item {{ Route::currentRouteNamed('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.home') }}">Admin Dashboard</a>
                    </li>
                @endif

                <!-- Authentication Links -->
                @guest

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Welcome Page</a>
                    </li>
                @else

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::guard('admin')->user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown1">
                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form2').submit();">
                                Logout
                            </a>

                            <form id="logout-form2" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
