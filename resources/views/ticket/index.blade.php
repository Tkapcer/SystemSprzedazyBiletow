@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Nazwa wydarzenia -->
    <div class="white-container">
        <h2 class="section-title">{{ $event->name }}</h2>
    </div>

    <!-- Informacje o wydarzeniu -->
    <div class="event-info">
        <p><strong>Data, godzina i miejsce: </strong>{{ \Carbon\Carbon::parse($event->event_date)->format('d F Y, H:i') }}, {{ $event->location }}</p>
    </div>

    <!-- Formularz do wyboru biletów -->
    <form action="{{ route('ticket.summary') }}" method="POST" id="ticket-form">
        @csrf
        <input type="hidden" name="eventId" value="{{ $event->id }}">
        
        <!-- Legenda -->
        <div class="seats-legend">
            <div class="legend-item">
                <div class="seat available"></div>
                <span>Dostępne</span>
            </div>
            <div class="legend-item">
                <div class="seat selected"></div>
                <span>Wybrane</span>
            </div>
            <div class="legend-item">
                <div class="seat occupied"></div>
                <span>Zajęte</span>
            </div>
        </div>

        <!-- Scena -->
        <div class="stage-container">
            <div class="stage">SCENA</div>
        </div>

        <!-- Mapa miejsc -->
        <div class="seating-map-container">
            <div class="sectors-wrapper" style="flex-direction: column; align-items: center;">
                @php
                    $parterSectors = [];
                    $balkonSectors = [];
                    
                    foreach ($sectorsWithSeats as $sectorDetails) {
                        // Zakładamy, że sektory z "parter" lub "Parter" w nazwie to parter, a pozostałe to balkon
                        if (stripos($sectorDetails['sector']->name, 'parter') !== false) {
                            $parterSectors[] = $sectorDetails;
                        } else {
                            $balkonSectors[] = $sectorDetails;
                        }
                    }
                @endphp
                
                <!-- Najpierw wyświetlamy parter -->
                @foreach ($parterSectors as $sectorDetails)
                    <div class="sector-map" data-sector-id="{{ $sectorDetails['sector']->id }}" style="width: 100%; max-width: 2000px;">
                        <h3 class="sector-name">{{ $sectorDetails['sector']->name }}</h3>
                        
                        <div class="seats-container">
                            @php
                                // Grupowanie miejsc według rzędów
                                $seatsByRow = [];
                                foreach ($sectorDetails['seats'] as $seat) {
                                    if (!isset($seatsByRow[$seat->row])) {
                                        $seatsByRow[$seat->row] = [];
                                    }
                                    $seatsByRow[$seat->row][$seat->column] = $seat;
                                }
                                
                                // Sortowanie rzędów numerycznie
                                uksort($seatsByRow, function($a, $b) {
                                    return intval($a) - intval($b);
                                });
                            @endphp
                            
                            @foreach ($seatsByRow as $row => $seatsInRow)
                                <div class="row-container">
                                    <div class="row-label">{{ $row }}</div>
                                    <div class="seats-row">
                                        @php
                                            // Sortowanie siedzeń w rzędzie numerycznie
                                            uksort($seatsInRow, function($a, $b) {
                                                return intval($a) - intval($b);
                                            });
                                        @endphp
                                        
                                        @foreach ($seatsInRow as $column => $seat)
                                            <div class="seat-wrapper" data-tooltip="Rząd: {{ $row }}, Miejsce: {{ $column }}, Cena: {{ $seat->price }} zł">
                                                <div class="seat {{ $seat->available ? 'available' : 'occupied' }}"
                                                     data-row="{{ $seat->row }}"
                                                     data-column="{{ $seat->column }}"
                                                     data-price="{{ $seat->price }}"
                                                     data-sector-id="{{ $sectorDetails['sector']->id }}">
                                                    {{ $column }}
                                                </div>
                                                @if ($seat->available)
                                                    <input type="checkbox"
                                                           class="seat-checkbox"
                                                           name="selected_seats[{{ $sectorDetails['sector']->id }}][]"
                                                           value="{{ $seat->row . '-' . $seat->column }}"
                                                           {{ $selectedSeatsMap->contains(function ($selectedSeat) use ($sectorDetails, $seat) {
                                                               return $selectedSeat['sector_id'] == $sectorDetails['sector']->id
                                                                   && $selectedSeat['row'] == $seat->row
                                                                   && $selectedSeat['column'] == $seat->column;
                                                           }) ? 'checked' : '' }}
                                                           style="display: none;">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                <!-- Następnie wyświetlamy balkon -->
                @foreach ($balkonSectors as $sectorDetails)
                    <div class="sector-map" data-sector-id="{{ $sectorDetails['sector']->id }}" style="width: 100%; max-width: 2000px; margin-top: 30px;">
                        <h3 class="sector-name">{{ $sectorDetails['sector']->name }}</h3>
                        
                        <div class="seats-container">
                            @php
                                // Grupowanie miejsc według rzędów
                                $seatsByRow = [];
                                foreach ($sectorDetails['seats'] as $seat) {
                                    if (!isset($seatsByRow[$seat->row])) {
                                        $seatsByRow[$seat->row] = [];
                                    }
                                    $seatsByRow[$seat->row][$seat->column] = $seat;
                                }
                                
                                // Sortowanie rzędów numerycznie
                                uksort($seatsByRow, function($a, $b) {
                                    return intval($a) - intval($b);
                                });
                            @endphp
                            
                            @foreach ($seatsByRow as $row => $seatsInRow)
                                <div class="row-container">
                                    <div class="row-label">{{ $row }}</div>
                                    <div class="seats-row">
                                        @php
                                            // Sortowanie siedzeń w rzędzie numerycznie
                                            uksort($seatsInRow, function($a, $b) {
                                                return intval($a) - intval($b);
                                            });
                                        @endphp
                                        
                                        @foreach ($seatsInRow as $column => $seat)
                                            <div class="seat-wrapper" data-tooltip="Rząd: {{ $row }}, Miejsce: {{ $column }}, Cena: {{ $seat->price }} zł">
                                                <div class="seat {{ $seat->available ? 'available' : 'occupied' }}"
                                                     data-row="{{ $seat->row }}"
                                                     data-column="{{ $seat->column }}"
                                                     data-price="{{ $seat->price }}"
                                                     data-sector-id="{{ $sectorDetails['sector']->id }}">
                                                    {{ $column }}
                                                </div>
                                                @if ($seat->available)
                                                    <input type="checkbox"
                                                           class="seat-checkbox"
                                                           name="selected_seats[{{ $sectorDetails['sector']->id }}][]"
                                                           value="{{ $seat->row . '-' . $seat->column }}"
                                                           {{ $selectedSeatsMap->contains(function ($selectedSeat) use ($sectorDetails, $seat) {
                                                               return $selectedSeat['sector_id'] == $sectorDetails['sector']->id
                                                                   && $selectedSeat['row'] == $seat->row
                                                                   && $selectedSeat['column'] == $seat->column;
                                                           }) ? 'checked' : '' }}
                                                           style="display: none;">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Podsumowanie wyboru -->
        <div class="selection-summary">
            <h3>Wybrane miejsca</h3>
            <div class="selected-seats-list"></div>
            <div class="total-price">Suma: <span>0</span> zł</div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seats = document.querySelectorAll('.seat.available');
    const selectedSeatsList = document.querySelector('.selected-seats-list');
    const totalPriceElement = document.querySelector('.total-price span');
    let totalPrice = 0;

    // Inicjalizacja - zaznaczenie wcześniej wybranych miejsc
    document.querySelectorAll('.seat-checkbox:checked').forEach(checkbox => {
        const seat = checkbox.previousElementSibling;
        seat.classList.add('selected');
        updateSeatSelection(seat, true);
    });

    // Dodanie nasłuchiwania kliknięć na miejsca
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            const checkbox = this.nextElementSibling;
            const isSelected = this.classList.toggle('selected');
            checkbox.checked = isSelected;
            
            updateSeatSelection(this, isSelected);
            updateTotalPrice();
        });
    });

    // Funkcja aktualizująca listę wybranych miejsc
    function updateSeatSelection(seat, isSelected) {
        const sectorId = seat.dataset.sectorId;
        const row = seat.dataset.row;
        const column = seat.dataset.column;
        const price = parseFloat(seat.dataset.price);
        const seatId = `seat-${sectorId}-${row}-${column}`;
        
        if (isSelected) {
            // Dodaj miejsce do listy
            const sectorName = document.querySelector(`.sector-map[data-sector-id="${sectorId}"] .sector-name`).textContent;
            const seatItem = document.createElement('div');
            seatItem.classList.add('selected-seat-item');
            seatItem.id = seatId;
            seatItem.innerHTML = `
                <div>Sektor: ${sectorName}, Rząd: ${row}, Miejsce: ${column}</div>
                <div>${price} zł</div>
            `;
            selectedSeatsList.appendChild(seatItem);
            totalPrice += price;
        } else {
            // Usuń miejsce z listy
            const seatItem = document.getElementById(seatId);
            if (seatItem) {
                selectedSeatsList.removeChild(seatItem);
                totalPrice -= price;
            }
        }
        
        totalPriceElement.textContent = totalPrice;
    }

    // Funkcja aktualizująca łączną cenę
    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll('.seat-checkbox:checked').forEach(checkbox => {
            const seat = checkbox.previousElementSibling;
            total += parseFloat(seat.dataset.price);
        });
        totalPriceElement.textContent = total;
    }

    // Inicjalizacja sumy
    updateTotalPrice();
});
</script>
@endsection
