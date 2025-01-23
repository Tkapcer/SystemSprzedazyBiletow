@extends('layouts.app')

@section('content')

<!-- Dane do testowania -->
@php
    $event = [
        'name' => 'C Występ Kabaretu 123',
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
    <h1 class="text-center mb-4">Wybór biletów</h1>

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

        <!-- Tabela wyboru biletów -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Rodzaj</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Liczba biletów</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sectors as $sector)
                    <tr>
                        <!-- Nazwa sektora -->
                        <td>{{ $sector['name'] }}</td>

                        <!-- Cena sektora -->
                        <td>{{ $sector['price'] }} zł</td>

                        <!-- Wybór liczby biletów -->
                        <td>
                            <input type="number" class="form-control" name="sectors[{{ $sector['id'] }}][number_of_seats]" value="0" min="0" max="10" required>
                            <input type="hidden" name="sectors[{{ $sector['id'] }}][sector_id]" value="{{ $sector['id'] }}">
                            <input type="hidden" name="sectors[{{ $sector['id'] }}][price]" value="{{ $sector['price'] }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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
