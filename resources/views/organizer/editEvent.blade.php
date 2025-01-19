@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edytuj wydarzenie</h1>
    <form action="{{ route('updateEvent', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Metoda PUT dla aktualizacji --}}

        @dump($errors)

        <!-- Tytuł -->
        <div class="mb-3">
            <label for="name" class="form-label">Tytuł</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                value="{{ old('name', $event->name) }}"
                required>
        </div>

        <!-- Lokalizacja -->
        <div class="mb-3">
            <label for="location" class="form-label">Lokalizacja</label>
            <input
                type="text"
                class="form-control"
                id="location"
                name="location"
                value="{{ old('location', $event->location) }}"
                required>
        </div>

        <!-- Opis -->
        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea
                class="form-control"
                id="description"
                name="description"
                rows="3"
                required>{{ old('description', $event->description) }}</textarea>
        </div>

        <!-- Data i godzina wydarzenia -->
        <div class="mb-3">
            <label for="event_date" class="form-label">Data i godzina wydarzenia</label>
            <input
                type="datetime-local"
                class="form-control"
                id="event_date"
                name="event_date"
                value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                required>
        </div>

        <!-- Zdjęcie -->
        <div class="mb-3">
            <label for="image" class="form-label">Zdjęcie (pozostaw puste, jeśli nie chcesz zmieniać)</label>
            <input
                type="file"
                class="form-control"
                id="image"
                name="image">
        </div>

        <!-- Sektory -->
        <div class="mb-3">
            <label class="form-label">Sektory</label>
            <div id="sectors-container">
                @foreach($event->sectors as $index => $sector)
                    <div class="sector-row mb-3 row">
                        <div class="col-md-4">
                            <input
                                type="hidden"
                                name="sectors[{{ $index }}][id]"
                                value="{{ $sector->id }}">
                            <input
                                type="text"
                                class="form-control"
                                name="sectors[{{ $index }}][name]"
                                value="{{ old('sectors.' . $index . '.name', $sector->name) }}"
                                placeholder="Nazwa sektora"
                                required>
                        </div>
                        <div class="col-md-4">
                            <input
                                type="number"
                                class="form-control"
                                name="sectors[{{ $index }}][seats]"
                                value="{{ old('sectors.' . $index . '.seats', $sector->seats) }}"
                                placeholder="Liczba miejsc"
                                min="1"
                                required>
                        </div>
                        <div class="col-md-4">
                            <input
                                type="number"
                                class="form-control"
                                name="sectors[{{ $index }}][price]"
                                value="{{ old('sectors.' . $index . '.price', $sector->price) }}"
                                placeholder="Cena biletu"
                                step="0.01"
                                min="0"
                                required>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>
@endsection
