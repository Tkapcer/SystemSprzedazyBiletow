<!DOCTYPE html>
<html lang="pl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel Użytkownika</title>
    </head>
    
    <!-- Kradziony nagłówek z welcome.blade.php -->
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

    @foreach ($users as $user)
    <main class="main-container"> <!-- można tak to zostawić czy mam zrobić nowy rodzaj klasy?-->
        <h2 class="{{ $user->name }}</h2> 
        <h3>{{ $user->email }}</h3>
        <h3>Twoje bilety</h3>
        @foreach ($tickets as $ticket)
            <div class="event-card">
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->name }}">
                <h3>{{ $ticket->event_name }}</h3>
                <p>

                    Data: {{ date('d F Y', strtotime($ticket->event_date)) }}<br>
                    Godzina: {{ date('H:i', strtotime($ticket->event_date)) }}<br>
                    Miejsce: {{$ticket->event_date}}<br>
                    Sektor: {{$ticket->sector}}<br>`
                </p>
                <a href="/event/{{ $event->id }}" class="btn-details">Zwrot biletu</a> <!-- prowadzi do zwrotu -->
                <a href="/event/{{ $event->id }}" class="btn-details">Szczegóły wydarzenia</a> <!-- prowadzi do strony wydarzenia -->
            </div>
        @endforeach
    </main>
    @endforeach

</body>
</html>
