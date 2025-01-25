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

            <p id="modal-message">Wciśnięto przycisk</p>
            <div id="sectors-summary"></div>
            <p><strong>Łączna kwota: </strong><span id="total-price">0 zł</span></p>

            <!-- Przycisk 'Wstecz' -->
            <button id="back-button" class="main-button-style btn-secondary">Wstecz</button>
            
            <!-- Dynamiczny przycisk (Kup lub Zarezerwuj) -->
            <button id="confirm-action-button" type="submit" class="main-button-style mt-3"></button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sectors = @json($sectors);  // Przekazujemy dane sektorów z PHP do JavaScriptu

    // Funkcja pokazująca modal z podsumowaniem
    function showModal(action) {
        const modalMessage = document.getElementById('modal-message');
        const confirmButton = document.getElementById('confirm-action-button');
        const sectorsSummary = document.getElementById('sectors-summary');
        const totalPriceElement = document.getElementById('total-price');
        let totalAmount = 0;
        let summaryHtml = '';

        const formData = new FormData(document.getElementById('ticket-form'));

        // Przetwarzanie danych sektorów i biletów
        sectors.forEach(sector => {
            const numberOfSeats = parseInt(formData.get('sectors[' + sector.id + '][number_of_seats]')) || 0;
            if (numberOfSeats > 0) {
                const price = sector.price;
                const total = numberOfSeats * price;
                totalAmount += total;
                summaryHtml += `<p>${sector.name}: ${numberOfSeats} x ${price} zł = ${total} zł</p>`;
            }
        });

        if (summaryHtml === '') {
            summaryHtml = '<p>Nie wybrano żadnych biletów.</p>';
        }

        sectorsSummary.innerHTML = summaryHtml;
        totalPriceElement.innerText = totalAmount + ' zł';

        // Przycisk Kup/Zarezerwuj
        if (action === 'buy') {
            modalMessage.innerText = "Wciśnięto przycisk 'Kup'";
            confirmButton.innerText = 'Kup';
            confirmButton.classList.add('btn-success');
            confirmButton.classList.remove('btn-warning');
            confirmButton.value = 'purchased';
            confirmButton.onclick = function() {
                document.getElementById('modal-form').submit();
            };
        } else if (action === 'reserve') {
            modalMessage.innerText = "Wciśnięto przycisk 'Zarezerwuj'";
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
