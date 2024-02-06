<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Role And Permission In Laravel 9 Tutorial - Techsolutionstuff') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"><!-- resources/views/layouts/app.blade.php -->

    <!-- Dodaj te linie przed zamknięciem sekcji head -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}" defer></script>

    <link rel="stylesheet" href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script>
        function goBack() {
          window.history.back();
        }
      </script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>TEST</strong>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                        @auth
                            @if(auth()->user()->hasRole('Admin'))

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ str_starts_with(request()->path(), 'users') ? 'active' : '' }}"  role="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" v-pre>Manage Users</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">Użytkownicy do zatwierdzenia</a> --}}
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">Użytkownicy</a>
                                    </div>
                                </li>

                                <li><a class="nav-link {{ str_starts_with(request()->path(), 'roles') ? 'active' : '' }}" href="{{ route('roles.index') }}">Manage Role</a></li>
                                <li><a class="nav-link {{ str_starts_with(request()->path(), 'products') ? 'active' : '' }}" href="{{ route('products.index') }}">Manage Product</a></li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ str_starts_with(request()->path(), 'words') ? 'active' : '' }}" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Słówka</a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">Użytkownicy do zatwierdzenia</a> --}}
                                        <a class="nav-link" href="{{ route('words.index') }}">Lista</a>
                                    </div>
                                </li>
                            {{-- @elseif(!auth()->user()->approved_add) --}}
                            @endif
                            <li class="nav-item dropdown">

                                {{-- <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Moje słówka</a> --}}

                                {{-- <a class="nav-link dropdown-toggle {{ request()->is('slowka') ? 'active' : '' }}" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Moje słówka</a> --}}
                                <a class="nav-link dropdown-toggle {{ str_starts_with(request()->path(), 'slowka') ? 'active' : '' }}" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Moje słówka</a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- <a class="dropdown-item" href="{{ route('admin.users.index') }}">Użytkownicy do zatwierdzenia</a> --}}
                                    <a class="dropdown-item" href="{{ route('slowka.index') }}">Tabele słowek</a>
                                </div>
                            </li>
                        @endauth
                        <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
        <footer>
            <div class="card-footer bg-transparent border-success">
                <div class="container text-center">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>Mariusz Moskwa</strong>
                </a>
                </div>
        </footer>
    </div>
</body>
</html>
