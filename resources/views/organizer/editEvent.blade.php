@extends('layouts.app')

@section('content')
<div class="container event-form">
    <h1>Edytuj wydarzenie</h1>
    <form action="{{ route('updateEvent', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Metoda PUT dla aktualizacji --}}

        <!-- Tytuł -->
        <div class="mb-3">
            <label for="name" class="form-label">Tytuł</label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name', $event->name) }}"
                required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Lokalizacja -->
        <div class="mb-3">
            <label for="location" class="form-label">Lokalizacja</label>
            <input
                type="text"
                class="form-control @error('location') is-invalid @enderror"
                id="location"
                name="location"
                value="{{ old('location', $event->location) }}"
                required>
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Opis -->
        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea
                class="form-control @error('description') is-invalid @enderror"
                id="description"
                name="description"
                rows="3"
                required>{{ old('description', $event->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Data i godzina wydarzenia -->
        <div class="mb-3">
            <label for="event_date" class="form-label">Data i godzina wydarzenia</label>
            <input
                type="datetime-local"
                class="form-control @error('event_date') is-invalid @enderror"
                id="event_date"
                name="event_date"
                value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                required>
            @error('event_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Zdjęcie -->
        <div class="mb-3">
            <label for="image" class="form-label">Zdjęcie (pozostaw puste, jeśli nie chcesz zmieniać)</label>
  
            <input
                type="file"
                class="form-control @error('image') is-invalid @enderror"
                id="image"
                name="image">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- Sektory -->
        <div class="mb-3">
            <label class="form-label">Sektory</label>
            <div id="sectors-container">
                @foreach($event->sectors as $index => $sector)
                    <div class="sector-row mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input
                                    type="hidden"
                                    name="sectors[{{ $index }}][id]"
                                    value="{{ $sector->id }}">
                                <input
                                    type="text"
                                    class="form-control @error("sectors.$index.name") is-invalid @enderror"
                                    name="sectors[{{ $index }}][name]"
                                    value="{{ old("sectors.$index.name", $sector->name) }}"
                                    placeholder="Nazwa sektora"
                                    required>
                                @error("sectors.$index.name")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input
                                    type="number"
                                    class="form-control @error("sectors.$index.seats") is-invalid @enderror"
                                    name="sectors[{{ $index }}][seats]"
                                    value="{{ old("sectors.$index.seats", $sector->seats) }}"
                                    placeholder="Liczba miejsc"
                                    min="1"
                                    required>
                                @error("sectors.$index.seats")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input
                                    type="number"
                                    class="form-control @error("sectors.$index.price") is-invalid @enderror"
                                    name="sectors[{{ $index }}][price]"
                                    value="{{ old("sectors.$index.price", $sector->price) }}"
                                    placeholder="Cena biletu"
                                    step="0.01"
                                    min="0"
                                    required>
                                @error("sectors.$index.price")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-sector" class="btn btn-secondary mt-2">Dodaj sektor</button>
            <button type="button" id="remove-sector" class="btn btn-danger mt-2">Usuń sektor</button>
        </div>

        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('sectors-container');
        const addSectorButton = document.getElementById('add-sector');
        const removeSectorButton = document.getElementById('remove-sector');
        let index = container.children.length;

        addSectorButton.addEventListener('click', () => {
            const sectorRow = document.createElement('div');
            sectorRow.classList.add('sector-row', 'mb-3');
            sectorRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <input
                            type="text"
                            class="form-control"
                            name="sectors[${index}][name]"
                            placeholder="Nazwa sektora"
                            required>
                    </div>
                    <div class="col-md-4">
                        <input
                            type="number"
                            class="form-control"
                            name="sectors[${index}][seats]"
                            placeholder="Liczba miejsc"
                            min="1"
                            required>
                    </div>
                    <div class="col-md-4">
                        <input
                            type="number"
                            class="form-control"
                            name="sectors[${index}][price]"
                            placeholder="Cena biletu"
                            step="0.01"
                            min="0"
                            required>
                    </div>
                </div>
            `;
            container.appendChild(sectorRow);
            index++;
        });

        removeSectorButton.addEventListener('click', () => {
            if (container.children.length > 0) {
                container.removeChild(container.lastElementChild);
                index--;
            }
        });
    });
</script>
@endsection
