@extends('layouts.app')

@section('content')
    <div class="main-container">
        <h2 class="section-title">Nadchodzące Wydarzenia</h2>

        @php
            // Przykładowe dane wydarzeń
            $events = [
                [
                    'name' => 'Koncert Zespołu XYZ',
                    'event_date' => '2024-12-20 19:00:00',
                    'location' => 'Hala Widowiskowa',
                    'image_path' => 'storage/events/koncert_1.jpg'
                ],
                [
                    'name' => 'Spektakl Teatralny ABC',
                    'event_date' => '2024-12-25 18:00:00',
                    'location' => 'Teatr Miejski',
                    'image_path' => 'storage/events/spektakl_1.jpg'
                ],
                [
                    'name' => 'Występ Kabaretu 123',
                    'event_date' => '2024-12-30 20:00:00',
                    'location' => 'Sala Koncertowa',
                    'image_path' => 'storage/events/kabaret_1.jpg'
                ],
            ];
        @endphp

        @foreach ($events as $event)
            <div class="event-card">
                <img src="{{ asset($event['image_path']) }}" alt="{{ $event['name'] }}">
                <h3>{{ $event['name'] }}</h3>
                <p>
                    Data: {{ date('d F Y', strtotime($event['event_date'])) }}<br>
                    Godzina: {{ date('H:i', strtotime($event['event_date'])) }}<br>
                    Miejsce: {{ $event['location'] }}
                </p>
                <a href="#" class="btn-details">Zobacz szczegóły</a>
            </div>
        @endforeach
    </div>
@endsection
