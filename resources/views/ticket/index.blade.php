@extends('layouts.app')

@section('content')

<!-- Dane do testowania -->
@php
    $event = [
        'name' => 'Występ Kabaretu 123',
        'event_date' => '2024-12-15 20:00:00',
        'location' => 'Sala Koncertowa',
        'image_path' => 'images_for_testing/o3.png'
    ];

    // Dane sektorów testowych
    $sectors = [
        ['id' => 1, 'name' => 'Sektor A', 'availableSeats' => 50, 'price' => 120],
        ['id' => 2, 'name' => 'Sektor B', 'availableSeats' => 30, 'price' => 100],
        ['id' => 3, 'name' => 'Sektor C', 'availableSeats' => 40, 'price' => 90],
    ];
@endphp

<div class="container">
    <!-- Nagłówek formularza zakupu biletów -->
    <div class="card-header">{{ __('Wybór biletów') }}</div>

    <!-- Informacje o wydarzeniu -->
    <div class="mb-4">
        <h2>{{ $event['name'] }}</h2>
        <p><strong>Data:</strong> {{ $event['event_date'] }}</p>
        <p><strong>Lokalizacja:</strong> {{ $event['location'] }}</p>
        <img src="{{ asset($event['image_path']) }}" alt="Image for {{ $event['name'] }}" class="img-fluid">
    </div>

    <!-- Formularz do zakupu/rezerwacji biletu -->
    <form action="{{ route('ticket.store') }}" method="POST">
        @csrf

        <!-- Wybór sektora -->
        <div class="form-group">
            <label for="sector_id">Wybierz sektor:</label>
            <select name="sector_id" id="sector_id" class="form-control" required>
                @foreach($sectors as $sector)
                    <option value="{{ $sector['id'] }}">
                        {{ $sector['name'] }} (Dostępne miejsca: {{ $sector['availableSeats'] }}, Cena: {{ $sector['price'] }} zł)
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Wybór liczby miejsc -->
        <div class="form-group">
            <label for="number_of_seats" class="form-label">Wybierz liczbę miejsc</label>
            <input type="number" class="form-control" id="number_of_seats" name="number_of_seats" value="1" required>
        </div>

        <!-- Dwa przyciski do wyboru akcji -->
        <div class="mt-3">
            <button type="submit" name="status" value="purchased" class="btn btn-success">
                Kup bilet
            </button>
            <button type="submit" name="status" value="reserved" class="btn btn-warning">
                Zarezerwuj bilet
            </button>
        </div>
    </form>
</div>

@endsection
