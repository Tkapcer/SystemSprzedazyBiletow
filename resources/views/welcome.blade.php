<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Sprzedaży Biletów</title>
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- Nagłówek -->
    <header class="header">
        <h1>System Sprzedaży Biletów</h1>
        @guest
            @if (Route::has('login'))
                <a class="nav-link" href="{{ route('login') }}">{{ __('Logowanie') }}</a>
            @endif

            @if (Route::has('register'))
                <a class="nav-link" href="{{ route('register') }}">{{ __('Rejestracja') }}</a>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    </header>

    <!-- Sekcja główna z wydarzeniami -->
    <main class="main-container">
        <h2 class="section-title">Nadchodzące Wydarzenia</h2>

        @foreach ($events as $event)
            <div class="event-card">
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->name }}">
                <h3>{{ $event->name }}</h3>
                <p>
                    Data: {{ date('d F Y', strtotime($event->event_date)) }}<br>
                    Godzina: {{ date('H:i', strtotime($event->event_date)) }}<br>
                    Miejsce: {{ $event->location }}
                </p>
                <a href="/event/{{ $event->id }}" class="btn-details">Zobacz szczegóły</a>
            </div>
        @endforeach

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Koncert Zespołu XYZ">
            <h3>Koncert Zespołu XYZ</h3>
            <p>Data: 20 grudnia 2024<br>Godzina: 19:00<br>Miejsce: Hala Widowiskowa</p>
            <a href="/event/1" class="btn-details">Zobacz szczegóły</a>
        </div>

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Spektakl Teatralny ABC">
            <h3>Spektakl Teatralny ABC</h3>
            <p>Data: 25 grudnia 2024<br>Godzina: 18:00<br>Miejsce: Teatr Miejski</p>
            <a href="/event/2" class="btn-details">Zobacz szczegóły</a>
        </div>

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Występ Kabaretu 123">
            <h3>Występ Kabaretu 123</h3>
            <p>Data: 30 grudnia 2024<br>Godzina: 20:00<br>Miejsce: Sala Koncertowa</p>
            <a href="/event/3" class="btn-details">Zobacz szczegóły</a>
        </div>
    </main>

</body>
</html>
