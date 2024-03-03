<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Polityka prywatności tarczy zegarka</title>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">

            </div>
        </nav>
    </div>

    <div>
        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
        <footer>
            <div class="card-footer bg-transparent border-success">
                <div class="container text-center">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>Mariusz M.</strong>
                </a>
                </div>
        </footer>
    </div>
</body>
</html>
