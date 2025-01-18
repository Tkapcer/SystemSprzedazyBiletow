@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
{{--        @dump($event)--}}

        <header class="header">
            <h1>Szczegóły Wydarzenia</h1>
            <a href="{{ url('/') }}" class="btn-back">Powrót do listy wydarzeń</a>
        </header>

        <main class="main-container">
            <div class="event-details">
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->name }}">
                <h2>{{ $event->name }}</h2>
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
            <a href="{{ route('ticket.index', $event->id) }}" class="btn btn-primary">Kup bilet</a>
        </main>
    </div>
</div>
@endsection
