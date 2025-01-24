@extends('layouts.app')

@section('content')
    <div class="main-container">
        <h2 class="section-title">Nadchodzące Wydarzenia</h2>

        <!-- Formularz do wyboru sortowania -->
        <div class="sort-options">
            <label for="sort-select">Sortuj według:</label>
            <select id="sort-select" onchange="sortEvents()">
                <option value="date-asc">Od najbliższych</option>
                <option value="date-desc">Od najdalszych</option>
                <option value="name-asc">Alfabetycznie A-Z</option>
                <option value="name-desc">Alfabetycznie Z-A</option>
            </select>
        </div>

        <!-- Pobieranie wydarzeń z bazy danych -->
        @php
            $events = \App\Models\Event::all(); // Pobiera wszystkie wydarzenia
        @endphp

        <!-- Lista wydarzeń -->
        <div class="events-container" id="events-container">
            @foreach ($events as $event)
                <div class="event-card" data-date="{{ $event->event_date }}" data-name="{{ $event->name }}">
                    <a href="/ticket/{{ $event->id }}"> <!-- Dodaj link do biletu -->
                        <img class="event-image" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}">
                        <h3>{{ $event->name }}</h3>
                        <p>
                            Data: {{ date('d F Y', strtotime($event->event_date)) }}<br>
                            Godzina: {{ date('H:i', strtotime($event->event_date)) }}<br>
                            Miejsce: {{ $event->location }}
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Funkcja do sortowania wydarzeń
        function sortEvents() {
            var sortValue = document.getElementById("sort-select").value;
            var eventsContainer = document.getElementById("events-container");
            var events = Array.from(eventsContainer.getElementsByClassName("event-card"));
            
            // Sortowanie wydarzeń w zależności od wybranego typu
            events.sort(function(a, b) {
                if (sortValue.includes("date")) {
                    var dateA = new Date(a.getAttribute("data-date"));
                    var dateB = new Date(b.getAttribute("data-date"));
                    if (sortValue === "date-asc") {
                        return dateA - dateB;  // Od najstarszych
                    } else {
                        return dateB - dateA;  // Od najnowszych
                    }
                } else if (sortValue.includes("name")) {
                    var nameA = a.getAttribute("data-name").toLowerCase();
                    var nameB = b.getAttribute("data-name").toLowerCase();
                    if (sortValue === "name-asc") {
                        return nameA.localeCompare(nameB);  // A-Z
                    } else {
                        return nameB.localeCompare(nameA);  // Z-A
                    }
                }
            });

            // Wyczyść i dodaj posortowane wydarzenia do kontenera
            eventsContainer.innerHTML = "";
            events.forEach(function(event) {
                eventsContainer.appendChild(event);
            });
        }
    </script>
@endsection
