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
    <form action="{{ route('ticket.summary') }}" method="POST" id="ticket-form">
    @csrf
        <input type="hidden" name="eventId" value="{{ $event->id }}">
        <div class="container">
            <h1>{{ $event->name }}</h1>

{{--            @dump($sectorsWithSeats)--}}
                @foreach ($sectorsWithSeats as $sectorDetails)
    {{--                @dump($sectorDetails)--}}
                    <div class="sector">
                        <h2>Sektor: {{ $sectorDetails['sector']->name }}</h2>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID sektora</th>
                                <th>Rząd</th>
                                <th>Kolumna</th>
                                <th>Cena</th>
                                <th>Dostępność</th>
                                <th>Wybierz</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sectorDetails['seats'] as $seat)
                                <tr>
                                    <td>{{ $sectorDetails['sector']->id }}</td>
                                    <td>{{ $seat->row }}</td>
                                    <td>{{ $seat->column }}</td>
                                    <td>{{ $seat->price }}</td>
                                    <td>{{ $seat->available ? 'Dostępne' : 'Zajęte' }}</td>
                                    <td>
                                        @if ($seat->available)
                                            <input type="checkbox"
                                                   name="selected_seats[{{ $sectorDetails['sector']->id }}][]"
                                                   value="{{ $seat->row . '-' . $seat->column }}"
{{--                                                   Sprawdzanie czy miejsce dostałoo wcześniej wybrane (mógł nastąpić powrót z podsumowania) --}}
                                                   {{ $selectedSeatsMap->contains(function ($selectedSeat) use ($sectorDetails, $seat) {
                                                           return $selectedSeat['sector_id'] == $sectorDetails['sector']->id
                                                               && $selectedSeat['row'] == $seat->row
                                                               && $selectedSeat['column'] == $seat->column;
                                                       }) ? 'checked' : '' }}
                                                   aria-label="Wybierz miejsce w rzędzie {{ $seat->row }}, kolumna {{ $seat->column }}">
{{--                                            <p>{{ $seat->id }}</p>--}}
                                        @else
                                            Niedostępne
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
        </div>


    @if ($errors->any())
       <div class="alert alert-danger invalid-feedback">
               @foreach ($errors->all() as $error)
                   <strong>{{ $error }}</strong>
               @endforeach
       </div>
    @endif

    <div class="white-container">
        <div class="button-container">
            <a href="/event/{{ $event->id }}" class="main-button-style btn-primary">Wstecz</a>
            <button type="submit" class="main-button-style">Dalej</button>
        </div>
    </div>
</form>
</div>

{{--<div id="confirmation-modal" class="modal">
    <div class="modal-content">
        <form action="{{ route('ticket.store') }}" method="POST" id="modal-form">
            @csrf

            <!-- Ukryte pole na sector_id -->
            <input type="hidden" name="sectors" id="sectors-data">

            <!-- Nagłówek modalu -->
            <h3 class="section-title mb-3">Wybrane bilety</h3>

            <!-- Podsumowanie wybranych biletów -->
            <div id="sectors-summary" class="mb-3"></div>

            <!-- Łączna kwota -->
            <p><strong>Łączna kwota: </strong><span id="total-price">0 zł</span></p>

            <!-- Przycisk Kup/Zarezerwuj -->
            <button id="confirm-action-button" type="submit" name="status" class="main-button-style mt-3"></button>

            <!-- Przycisk Wstecz -->
            <button id="back-button" class="main-button-style btn-secondary mt-3">Wstecz</button>
        </form>
    </div>
</div>--}}


<script>
    /*document.addEventListener('DOMContentLoaded', function() {
        const sectors = {{--@json($sectors)--}};

        /!*!// Funkcja do resetowania liczby biletów w innych sektorach
        function resetOtherSectors(currentSectorId) {
            // Pobieramy wszystkie pola input z liczbą biletów
            const sectorInputs = document.querySelectorAll('input[name^="sectors["][name$="][number_of_seats]');

            // Iterujemy po wszystkich polach input
            sectorInputs.forEach(input => {
                const sectorId = input.name.match(/sectors\[(\d+)\]\[number_of_seats\]/)[1]; // Wyciągamy ID sektora z nazwy inputa

                // Jeśli to nie jest ten sam sektor, resetujemy jego liczbę biletów
                if (sectorId !== currentSectorId.toString()) {
                    input.value = 0;
                }
            });
        }*!/

        // Nasłuchujemy zmian na inputach liczby biletów
        const sectorInputs = document.querySelectorAll('input[name^="sectors["][name$="][number_of_seats]');

        sectorInputs.forEach(input => {
            input.addEventListener('input', function() {
                const currentSectorId = input.name.match(/sectors\[(\d+)\]\[number_of_seats\]/)[1]; // Wyciągamy ID sektora

                // Jeśli użytkownik ustawi liczbę biletów na więcej niż 0, resetujemy inne sektory
                if (parseInt(input.value, 10) > 0) {
                    resetOtherSectors(currentSectorId);
                }
            });
        });

        // Funkcja do sprawdzania formularza
        function validateForm() {
            const form = document.getElementById('ticket-form');
            let isValid = false;
            let errorMessage = '';

            // Pobieramy dane formularza
            const formData = new FormData(form);
            const sectorInputs = form.querySelectorAll('input[name^="sectors["][name$="][number_of_seats]');

            // Sprawdzamy, czy wybrano przynajmniej 1 bilet w jednym z sektorów
            sectorInputs.forEach(input => {
                const numberOfSeats = parseInt(input.value, 10);
                if (numberOfSeats > 0) {
                    isValid = true; // Jeśli wybrano jakikolwiek bilet, formularz jest poprawny
                }
            });

            // Wyświetlamy komunikat o błędzie lub kontynuujemy
            if (errorMessage) {
                alert(errorMessage);
                return false; // Zapobiega wysłaniu formularza
            }
            return true; // Pozwala wysłać formularz
        }

        // Funkcja pokazująca modal z podsumowaniem
        function showModal(action) {
            const confirmButton = document.getElementById('confirm-action-button');
            const sectorsSummary = document.getElementById('sectors-summary');
            const totalPriceElement = document.getElementById('total-price');
            let totalAmount = 0;
            let summaryHtml = '';

            const formData = new FormData(document.getElementById('ticket-form'));

            let isAnyTicketSelected = false;
            let isInvalidTicketCount = false;

            let selectedSectors = [];  // Zmienna do przechowywania danych o wybranych biletach

            // Przetwarzanie danych sektorów i biletów
            sectors.forEach(sector => {
                const numberOfSeats = parseInt(formData.get('sectors[' + sector.id + '][number_of_seats]')) || 0;
                if (numberOfSeats > 0) {
                    const price = sector.price;
                    const total = numberOfSeats * price;
                    totalAmount += total;
                    summaryHtml += `<p>${sector.name}: ${numberOfSeats} x ${price} zł = ${total} zł</p>`;
                    selectedSectors.push({
                        sector_id: sector.id,
                        price: price,
                        number_of_seats: numberOfSeats
                    });
                    isAnyTicketSelected = true;
                }

                // Sprawdzanie, czy liczba biletów w danym sektorze nie przekracza 10
                if (numberOfSeats > 10) {
                    isInvalidTicketCount = true;
                }
            });

            // Jeśli nie wybrano żadnych biletów
            if (!isAnyTicketSelected) {
                summaryHtml = '<p>Nie wybrano żadnych biletów.</p>';
            }

            // Jeśli wybrano za dużo biletów w jednym z sektorów
            if (isInvalidTicketCount) {
                summaryHtml = '<p>Błędna liczba biletów. Maksymalna liczba to 10 biletów w jednej strefie.</p>';
            }

            sectorsSummary.innerHTML = summaryHtml;
            totalPriceElement.innerText = totalAmount + ' zł';

            // Przekazanie danych o wybranych sektorach do ukrytego pola formularza
            document.getElementById('sectors-data').value = JSON.stringify(selectedSectors);

            // Pokazujemy przycisk Kup/Zarezerwuj tylko jeśli wybrano bilety
            if (isAnyTicketSelected && !isInvalidTicketCount) {
                confirmButton.style.display = 'block';
            } else {
                confirmButton.style.display = 'none';
            }

            // Przycisk Kup/Zarezerwuj
            if (action === 'buy') {
                confirmButton.innerText = 'Kup';
                confirmButton.classList.add('btn-success');
                confirmButton.classList.remove('btn-warning');
                confirmButton.value = 'purchased';
            } else if (action === 'reserve') {
                confirmButton.innerText = 'Zarezerwuj';
                confirmButton.classList.add('btn-warning');
                confirmButton.classList.remove('btn-success');
                confirmButton.value = 'reserved';
            }

            // Pokazujemy modal
            document.getElementById('confirmation-modal').style.display = 'flex';
        }*/


        /*function getFormData() {
            const formData = new FormData(document.getElementById('ticket-form'));
            let sectorsData = [];

            sectors.forEach(sector => {
                const numberOfSeats = parseInt(formData.get('sectors[' + sector.id + '][number_of_seats]')) || 0;
                if (numberOfSeats > 0) {
                    sectorsData.push({
                        sector_id: sector.id,
                        price: sector.price,
                        number_of_seats: numberOfSeats
                    });
                }
            });

            return sectorsData;
        }

        // Obsługa przycisków
        document.getElementById('buy-button').addEventListener('click', function() {
            if (validateForm()) {
                showModal("buy");
            }
        });

        document.getElementById('reserve-button').addEventListener('click', function() {
            if (validateForm()) {
                showModal("reserve");
            }
        });

        // Przycisk 'Wstecz' - ukrywa modal
        document.getElementById('back-button').addEventListener('click', function() {
            event.preventDefault();
            document.getElementById('confirmation-modal').style.display = 'none';
        });
    });*/

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
