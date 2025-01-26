<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamiczny tytuł strony -->
    <title>@yield('title', 'Viva La Billete')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/icons8-ticket-50.png') }}" type="image/png">

    <!-- Linki do fontów -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Wczytywanie styli i skryptów -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dodanie CDN do ColorThief -->
    <script src="https://cdn.jsdelivr.net/npm/colorthief@2.3.0/dist/color-thief.umd.js"></script>

    <!-- Własny styl do tła strony, musi być tu, bo w app.css nie wczytuje się ścieżka do obrazu. -->
    <style>
        body {
            background-image: url('{{ asset('storage/tlo3.png') }}');
            background-repeat: repeat;
            background-position: top left;
            background-size: auto;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Pasek nawigacyjny -->
        <nav class="navbar">
            <div class="nav-link">
                <a class="main-button-style" href="/">Strona Główna</a>
            </div>
            <div class="navbar-title" style="background-image: linear-gradient(to right, #ffabf4, #bd8aff, #99f5ff); color: transparent;  background-clip: text; -webkit-background-clip: text;">Viva La Billete</div>
            <div class="navbar-nav">
                <!-- Sprawdzamy, czy użytkownik jest zalogowany jako klient, organizator lub administrator -->
                @if(Auth::guard('web')->check())
                    <!-- Dla klienta -->
                        <span>{{ Auth::user()->name }} {{ Auth::user()->surname }}</span>
                        <span>{{ Auth::user()->balance }} zł</span>
                        <a class="main-button-style" href="{{ route('home') }}">Konto</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="main-button-style">Wyloguj</button>
                        </form>
                @elseif(Auth::guard('organizer')->check())
                    <!-- Dla organizatora -->
                        <span>{{ Auth::guard('organizer')->user()->companyName }}</span> 
                        <a class="main-button-style" href="{{ route('organizer.panel') }}">Konto</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="main-button-style">Wyloguj</button> <!-- Zmieniony przycisk -->
                        </form>
                @elseif(Auth::guard('admin')->check())
                    <!-- Dla administratora -->
                        <span>{{ Auth::guard('admin')->user()->name }} {{ Auth::guard('admin')->user()->surname }}</span> 
                        <a class="main-button-style" href="{{ route('adminPanel') }}">Panel</a> 
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="main-button-style">Wyloguj</button> <!-- Zmieniony przycisk -->
                        </form>
                @else
                    <!-- Jeśli użytkownik nie jest zalogowany jako klient, organizator ani administrator -->
                    <a class="nav-link main-button-style" href="{{ route('register') }}">Rejestracja</a> 
                    <a class="nav-link main-button-style" href="{{ route('login') }}">Logowanie</a> 
                @endif
            </div>
        </nav>

        <main class="py-4">
            @yield('content') <!-- Główna zawartość strony -->
        </main>
    </div>

</body>
</html>
