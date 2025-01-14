<!DOCTYPE html>
<html lang="pl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Szczegóły wydarzenia</title>
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

    @foreach ($events as $event)
    <main class="main-container"> <!-- można tak to zostawić, czy dać "btn-details", czy mam zrobić nowy rodzaj klasy?-->
        <h2 class="{{ $event->name }}</h2> 
        <h3>{{ $event->description }}</h3>
        <h3>Data: {{ date('d F Y', strtotime($event->event_date)) }}</h3><br>
        <h3>Godzina: {{ date('H:i', strtotime($event->event_date)) }}</h3><br>
        <h3>Miejsce: {{ $event->location }}</h3><br>
        @foreach ($sectors as $sector)
            <div class="event-card">
                <h3>{{ $sector->name }}</h3>
                <p>
                    Cena: {{$sector->price}}<br>
                    Pozostało: {{$sector->seats}
                </p>
                <a href="/event/{{ $event->id }}" class="btn-details">Zarezerwuj</a> <!-- prowadzi do rezerwacji -->
                <a href="/event/{{ $event->id }}" class="btn-details">Kup od razu</a> <!-- prowadzi do koszyka -->
            </div>
        @endforeach
    </main>
    @endforeach

</body>
</html>
