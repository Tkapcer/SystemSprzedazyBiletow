<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Pasek nawigacyjny -->
        <nav class="navbar">
            <a class="nav-link" href="/">Strona Główna</a>
            <div class="navbar-nav">
                <!-- Jeśli użytkownik nie jest zalogowany -->
                @guest
                    <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                    <a class="nav-link" href="{{ route('login') }}">Logowanie</a>
                @else
                    <!-- Dla klientów -->
                    @auth('web')
                        <span class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-saldo">{{ Auth::user()->balance }} zł</span>
                            <a class="nav-link" href="{{ route('home') }}">Konto</a>
                            <a class="nav-link" href="{{ route('home') }}">Doładuj</a> <!-- Przekierowanie na stronę konta klienta -->

                            <!-- Formularz wylogowywania -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link logout" style="background: none; border: none; color: inherit; padding: 5px 10px;">Wyloguj</button>
                            </form>
                        </span>
                    @endauth

                    <!-- Dla organizatorów -->
                    @auth('organizer')
                        <span class="user-info">
                            <span class="user-name">{{ Auth::user()->company_name }}</span>
                            <a class="nav-link" href="{{ route('panel') }}">Panel</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link logout" style="background: none; border: none; color: inherit; padding: 5px 10px;">Wyloguj</button>
                            </form>
                        </span>
                    @endauth
                @endguest
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
