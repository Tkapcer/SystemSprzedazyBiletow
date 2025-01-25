@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Nagłówek formularza wyboru biletów -->
    <h1 class="text-center mb-4">Wybór biletów</h1>

    <!-- Informacje o wydarzeniu -->
    <div class="mb-4">
        <h2>{{ $event->name }}</h2>
        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y H:i') }}</p>
        <p><strong>Lokalizacja:</strong> {{ $event->location }}</p>
        <img src="{{ asset('storage/' . $event->image_path) }}" alt="Image for {{ $event->name }}" class="img-fluid">
    </div>

    <!-- Formularz do wyboru biletów -->
    <form action="{{ route('home') }}" method="POST" id="ticket-form">
        @csrf

        <!-- Tabela wyboru biletów -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Rodzaj</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Liczba biletów</th>
                </tr>
            </thead>
            <tbody id="sectors-list">
                <!-- Dynamicznie generowane sektory -->
                @foreach ($sectors as $sector)
                    <tr>
                        <td>{{ $sector->name }}</td>
                        <td>{{ $sector->price }} zł</td>
                        <td>
                            <input type="number" class="form-control" name="sectors[{{ $sector->id }}][number_of_seats]" value="0" min="0" max="10" required>
                            <input type="hidden" name="sectors[{{ $sector->id }}][sector_id]" value="{{ $sector->id }}">
                            <input type="hidden" name="sectors[{{ $sector->id }}][price]" value="{{ $sector->price }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <!-- Trzy przyciski -->
        <div class="mt-3 flex justify-between gap-2">
            <a href="/event/{{ $event->id }}" class="main-button-style btn-secondary w-1/2">
                Wstecz
            </a>
            <button type="button" id="buy-button" class="main-button-style btn-success w-1/2">
                Kup
            </button>
            <button type="button" id="reserve-button" class="main-button-style btn-warning w-1/2">
                Zarezerwuj
            </button>
        </div>
    </form>
</div>

<!-- Modal z powiadomieniem -->
<div id="confirmation-modal" class="modal">
    <div class="modal-content">
        <form action="{{ route('ticket.store') }}" method="POST" id="modal-form">
            @csrf

            <!-- Nagłówek modalu -->
            <h3 class="mb-3">Wybrane bilety</h3>

            <!-- Podsumowanie wybranych biletów -->
            <div id="sectors-summary" class="mb-3"></div>
            
            <!-- Łączna kwota -->
            <p><strong>Łączna kwota: </strong><span id="total-price">0 zł</span></p>

            <!-- Przycisk Kup/Zarezerwuj -->
            <button id="confirm-action-button" type="submit" class="main-button-style mt-3"></button>

            <!-- Przycisk Wstecz -->
            <button id="back-button" class="main-button-style btn-secondary mt-3">Wstecz</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sectors = @json($sectors);

    // Funkcja pokazująca modal z podsumowaniem
    function showModal(action) {
        const confirmButton = document.getElementById('confirm-action-button');
        const sectorsSummary = document.getElementById('sectors-summary');
        const totalPriceElement = document.getElementById('total-price');
        const buyButton = document.getElementById('buy-button');
        let totalAmount = 0;
        let summaryHtml = '';

        const formData = new FormData(document.getElementById('ticket-form'));

        let isAnyTicketSelected = false; // Zmienna sprawdzająca, czy wybrano jakiekolwiek bilety

        // Przetwarzanie danych sektorów i biletów
        sectors.forEach(sector => {
            const numberOfSeats = parseInt(formData.get('sectors[' + sector.id + '][number_of_seats]')) || 0;
            if (numberOfSeats > 0) {
                const price = sector.price;
                const total = numberOfSeats * price;
                totalAmount += total;
                summaryHtml += `<p>${sector.name}: ${numberOfSeats} x ${price} zł = ${total} zł</p>`;
                isAnyTicketSelected = true; // Jeśli wybrano bilety, ustawiamy tę zmienną na true
            }
        });

        if (summaryHtml === '') {
            summaryHtml = '<p>Nie wybrano żadnych biletów.</p>';
        }

        sectorsSummary.innerHTML = summaryHtml;
        totalPriceElement.innerText = totalAmount + ' zł';

        // Sprawdzamy, czy użytkownik wybrał bilety
        if (!isAnyTicketSelected) {
            // Jeśli nie wybrano biletów, przycisk Kup/Zarezerwuj będzie ukryty lub dezaktywowany
            confirmButton.style.display = 'none'; // Ukrywamy przycisk
        } else {
            confirmButton.style.display = 'block'; // Pokazujemy przycisk, jeśli wybrano bilety
        }

        // Przycisk Kup/Zarezerwuj
        if (action === 'buy') {
            confirmButton.innerText = 'Kup';
            confirmButton.classList.add('btn-success');
            confirmButton.classList.remove('btn-warning');
            confirmButton.value = 'purchased';
            confirmButton.onclick = function() {
                const ticketId = document.getElementById('ticket-id').value; // Załóżmy, że ID biletu jest w ukrytym polu

                // Wysyłamy zapytanie AJAX do płatności
                fetch(`/ticket/pay/${ticketId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        sectors: getFormData()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalMessage.innerText = 'Płatność zakończona sukcesem';
                    } else {
                        modalMessage.innerText = data.message; // Wyświetlamy komunikat o błędzie
                    }
                });
            };
        } else if (action === 'reserve') {
            confirmButton.innerText = 'Zarezerwuj';
            confirmButton.classList.add('btn-warning');
            confirmButton.classList.remove('btn-success');
            confirmButton.value = 'reserved';
            confirmButton.onclick = function() {
                document.getElementById('modal-form').submit();
            };
        }

        // Pokazujemy modal
        document.getElementById('confirmation-modal').style.display = 'flex';
    }

    function getFormData() {
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
        showModal("buy");
    });

    document.getElementById('reserve-button').addEventListener('click', function() {
        showModal("reserve");
    });

    // Przycisk 'Wstecz' - ukrywa modal
    document.getElementById('back-button').addEventListener('click', function() {
        event.preventDefault();
        document.getElementById('confirmation-modal').style.display = 'none';
    });
});

</script>

@endsection
