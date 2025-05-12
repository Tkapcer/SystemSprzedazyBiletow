@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Nazwa wydarzenia -->
        <div class="white-container">
             <h2 class="section-title">{{ $event->name }}</h2>
        </div>

    <!-- Informacje o wydarzeniu -->
    <div class="event-info">
        <p><strong>Data, godzina i miejsce: </strong> {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y,  H:i') }},  {{ $event->location }}</p><br>
    </div>

    <!-- Formularz do wyboru biletów -->
{{--    @include($request)--}}
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">Sektor</th>
                <th scope="col">Rząd</th>
                <th scope="col">Kolumna</th>
                <th scope="col">Cena</th>
            </tr>
        </thead>
        <tbody>
{{--        @dump($selectedSeats)--}}
            @foreach($selectedSeats as $seat)
                <tr>
                    <td>{{ $seat->sector_id }}</td>
                    <td>{{ $seat->row }}</td>
                    <td>{{ $seat->column }}</td>
                    <td>{{ $seat->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-end mt-3">
        <strong>Całkowita suma: {{ $totalPrice }} zł</strong>
    </div>

    <form action="{{ route('ticket.store') }}" method="POST" id="ticket-form">
        @csrf

        <div class="white-container">
            <div class="button-container">
                <button type="submit" name="status" value="purchased" id="buy-button" class="main-button-style">Kup</button>
                <button type="submit" name="status" value="reserved" id="reserve-button" class="main-button-style">Zarezerwuj</button>
            </div>
            <a href="/ticket/{{ $event->id }}" class="main-button-style btn-primary">Wstecz</a>
        </div>

    </form>

</div>

<script>
    // Skrypt związany z ColorThief
    document.addEventListener('DOMContentLoaded', function() {
        const colorThief = new ColorThief();
        const eventImage = new Image();
        eventImage.src = "{{ asset('storage/' . $event->image_path) }}";
        const eventContainer = document.querySelector('.container');


        if (eventImage) {
            // Sprawdzamy, czy obrazek jest już załadowany
            if (eventImage.complete) {
                setBackgroundColor(eventImage);
            } else {
                // Czekamy na załadowanie obrazka, jeśli jeszcze nie jest załadowany
                eventImage.addEventListener('load', function() {
                    setBackgroundColor(eventImage);
                });
            }

            // Obsługuje błąd, jeśli obrazek się nie załaduje
            eventImage.addEventListener('error', function() {
                setBackgroundColor(null);
            });
        }

        // Funkcja ustalająca tło kontenera na podstawie dominującego koloru
        function setBackgroundColor(img) {
            try {
                // Sprawdzamy, czy obrazek jest wystarczająco duży dla ColorThief
                if (img && img.naturalWidth > 0 && img.naturalHeight > 0) {
                    const dominantColor = colorThief.getColor(img);
                    const rgbColor = `rgb(${dominantColor.join(', ')})`;

                    // Ustawiamy tło dla kontenera
                    eventContainer.style.backgroundColor = rgbColor;
                } else {
                    // Jeśli obrazek jest niewłaściwy lub nie ma go, generujemy losowy kolor
                    const randomColor = getRandomColor();
            eventContainer.style.backgroundColor = randomColor;
                }
            } catch (error) {
                console.error("Error while extracting dominant color:", error);
                // W przypadku błędu generujemy losowy kolor
                const randomColor = getRandomColor();
            eventContainer.style.backgroundColor = randomColor;
            }
        }

        // Funkcja generująca prawie losowy kolor w formacie RGB
        function getRandomColor() {
            const r = Math.floor(Math.random() * 200);
            const g = Math.floor(Math.random() * 200);
            const b = Math.floor(Math.random() * 200);
            return `rgb(${r}, ${g}, ${b})`;
        }
    });
</script>

@endsection
