<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamiczny tytuł strony -->
    <title>@yield('title', 'Viva La Billete')</title>

    <!-- Linki do fontów -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Wczytywanie styli i skryptów -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dodanie CDN do ColorThief -->
    <script src="https://cdn.jsdelivr.net/npm/colorthief@2.3.0/dist/color-thief.umd.js"></script>
</head>
<body>
    <div id="app">
        <!-- Pasek nawigacyjny -->
        <nav class="navbar">
            <a class="nav-link" href="/">Strona Główna</a>
            <div class="navbar-title">Viva La Billete</div>
            <div class="navbar-nav">
                @guest
                    <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                    <a class="nav-link" href="{{ route('login') }}">Logowanie</a>
                @else
                    @auth('web')
                        <span class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-saldo">{{ Auth::user()->balance }} zł</span>
                            <a class="nav-link" href="{{ route('home') }}">Konto</a>
                            <a class="nav-link" href="{{ route('home') }}">Doładuj</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link logout" style="background: none; border: none; color: inherit; padding: 5px 10px;">Wyloguj</button>
                            </form>
                        </span>
                    @endauth
                    @auth('organizer')
                        <span class="user-info">
                            <span class="user-name">{{ Auth::user()->company_name }}</span>
                            <a class="nav-link" href="{{ route('panel') }}">Konto</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link logout" style="background: none; border: none; color: inherit; padding: 5px 10px;">Wyloguj</button>
                            </form>
                        </span>
                    @endauth
                    @auth('admin')
                        <span class="user-info">
                            <span class="user-name">{{ Auth::user()->company_name }}</span>
                            <a class="nav-link" href="{{ route('adminPanel') }}">Panel</a>
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
            @yield('content') <!-- Główna zawartość strony -->
        </main>
    </div>

    <!-- Wspólny skrypt dla wszystkich stron -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorThief = new ColorThief(); // Upewnij się, że masz dostęp do ColorThief
            const eventCards = document.querySelectorAll('.event-card');

            eventCards.forEach(card => {
                const img = card.querySelector('.event-image');

                if (img) {
                    // Sprawdzamy, czy obrazek jest już załadowany
                    if (img.complete) {
                        setCardBackgroundColor(img, card); // Ustawiamy tło od razu
                    } else {
                        // Czekamy na załadowanie obrazka, jeśli jeszcze nie jest załadowany
                        img.addEventListener('load', function() {
                            setCardBackgroundColor(img, card); // Ustawiamy tło po załadowaniu obrazu
                        });
                    }
                }
            });

            // Funkcja ustalająca tło karty na podstawie dominującego koloru obrazu
            function setCardBackgroundColor(img, card) {
                try {
                    // Sprawdzamy, czy obrazek jest wystarczająco duży dla ColorThief
                    if (img.naturalWidth > 0 && img.naturalHeight > 0) {
                        const dominantColor = colorThief.getColor(img);
                        const rgbColor = `rgb(${dominantColor.join(', ')})`;

                        // Ustawiamy tło karty
                        card.style.backgroundColor = rgbColor;

                        // Ustawiamy tło linku wewnątrz karty
                        const link = card.querySelector('a');
                        if (link) {
                            link.style.backgroundColor = rgbColor;
                        }
                    }
                } catch (error) {
                    console.error("Error while extracting dominant color:", error);
                }
            }
        });
    </script>
</body>
</html>
