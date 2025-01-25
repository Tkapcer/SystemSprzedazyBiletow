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
            background-image: url('{{ asset('storage/tlo.png') }}');
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
            <a class="nav-link main-button-style" href="/">Strona Główna</a>
            <div class="navbar-title">Viva La Billete</div>
            <div class="navbar-nav">
                <!-- Sprawdzamy, czy użytkownik jest zalogowany jako klient, organizator lub administrator -->
                @if(Auth::guard('web')->check())
                    <!-- Dla klienta -->
                    <span class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-saldo">{{ Auth::user()->balance }} zł</span>
                        <a class="nav-link main-button-style" href="{{ route('home') }}">Konto</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link main-button-style">Wyloguj</button>
                        </form>
                    </span>
                @elseif(Auth::guard('organizer')->check())
                    <!-- Dla organizatora -->
                    <span class="user-info">
                        <a class="nav-link main-button-style" href="{{ route('organizer.panel') }}">Konto</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link main-button-style">Wyloguj</button> <!-- Zmieniony przycisk -->
                        </form>
                    </span>
                @elseif(Auth::guard('admin')->check())
                    <!-- Dla administratora -->
                    <span class="user-info">
                        <a class="nav-link main-button-style" href="{{ route('adminPanel') }}">Panel</a> 
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link main-button-style" style="background: none; border: none; color: inherit; padding: 5px 10px;">Wyloguj</button> <!-- Zmieniony przycisk -->
                        </form>
                    </span>
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
