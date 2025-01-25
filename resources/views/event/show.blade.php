@extends('layouts.app')

@section('content')
<div class="container" id="event-container">
    <div class="events-container" id="event-row">
        {{-- @dump($event) --}}

        <!-- Nazwa wydarzenia -->
        <div class="white-container">
             <h2>{{ $event->name }}</h2>
        </div>
        <!-- Kontener zdjęcia i treści w jednym wierszu -->
        <div class="event-content">
            <!-- Zdjęcie -->
            <div class="event-image-container">
                <img id="event-image" src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->name }}">
            </div>

            <!-- Opis-->
            <div class="event-details">
                <main class="main-container" id="main-container">
                    <div class="event-info">
                        <p>
                            <strong>Data:</strong> {{ date('d F Y', strtotime($event->event_date)) }}<br>
                            <strong>Godzina:</strong> {{ date('H:i', strtotime($event->event_date)) }}<br>
                            <strong>Miejsce:</strong> {{ $event->location }}
                        </p>
                        <div class="description">
                            <strong>Opis:</strong>
                            <p>{{ $event->description }}</p>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="white-container">
            <a href="{{ route('ticket.index', $event->id) }}" class="main-button-style btn-primary">Kup / Zarezerwuj</a>
            <a href="{{ url('/') }}" class="main-button-style btn-primary">Powrót do listy wydarzeń</a>
        </div>
    </div>
</div>

<!-- Skrypt związany z ColorThief -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorThief = new ColorThief(); // Upewnij się, że masz dostęp do ColorThief
        const eventImage = document.getElementById('event-image'); // Pobieramy obrazek wydarzenia
        const eventContainer = document.getElementById('event-container'); // Pobieramy kontener, który chcemy zmienić tło

        // Czekamy, aż obrazek zostanie załadowany
        if (eventImage.complete) {
            setBackgroundColor(eventImage); // Jeśli obrazek już jest załadowany, ustawiamy kolor
        } else {
            eventImage.addEventListener('load', function() {
                setBackgroundColor(eventImage); // Jeśli obrazek nie jest jeszcze załadowany, ustawiamy kolor po jego załadowaniu
            });
        }

        // Funkcja ustalająca tło kontenera na podstawie dominującego koloru
        function setBackgroundColor(img) {
            try {
                // Sprawdzamy, czy obrazek jest wystarczająco duży dla ColorThief
                if (img.naturalWidth > 0 && img.naturalHeight > 0) {
                    const dominantColor = colorThief.getColor(img); // Pobieramy dominujący kolor
                    const rgbColor = `rgb(${dominantColor.join(', ')})`; // Zamieniamy na format RGB

                    // Ustawiamy tło dla kontenera .container
                    eventContainer.style.backgroundColor = rgbColor;

                    // Możesz opcjonalnie ustawić również tło innych elementów w kontenerze, np. tekstów.
                    // eventRow.style.color = getContrastingTextColor(dominantColor);
                }
            } catch (error) {
                console.error("Error while extracting dominant color:", error);
            }
        }

        // Funkcja do obliczania kontrastującego koloru tekstu na podstawie dominującego koloru (opcjonalnie)
        function getContrastingTextColor(rgb) {
            const [r, g, b] = rgb;
            const brightness = 0.2126 * r + 0.7152 * g + 0.0722 * b; // Luma formula
            return brightness > 128 ? 'black' : 'white'; // Jeśli tło jest jasne, tekst będzie czarny, w przeciwnym razie biały
        }
    });
</script>
@endsection

