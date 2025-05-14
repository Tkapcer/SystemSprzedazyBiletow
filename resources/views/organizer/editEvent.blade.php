@extends('layouts.app')

@section('content')
<div class="container event-form">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="section-title">{{ __('Edytuj wydarzenie') }}</div>

                <div class="card-body">
                    <form action="{{ route('updateEvent', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Tytuł -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Tytuł</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $event->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Opis -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Data i godzina wydarzenia -->
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Data i godzina wydarzenia</label>
                            <input type="datetime-local" class="form-control @error('event_date') is-invalid @enderror"
                                   id="event_date" name="event_date"
                                   value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
                            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Zdjęcie -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Zdjęcie (pozostaw puste, jeśli nie chcesz zmieniać)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Sala -->
                        <div class="mb-3">
                            <label for="venue_id" class="form-label">Sala</label>
                            <select class="form-control @error('venue_id') is-invalid @enderror"
                                    id="venue_id" name="venue_id" required>
                                <option value="">-- Wybierz salę --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ (old('venue_id', $event->venue_id) == $venue->id) ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Sektory -->
                        <div id="sectors-container">
                            @foreach ($venues as $venue)
                                <div class="sectors-list" data-venue-id="{{ $venue->id }}" style="display: none;">
                                    <h5>Sektory dla sali: {{ $venue->name }}</h5>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Nazwa sektora</th>
                                            <th>Rzędy</th>
                                            <th>Kolumny</th>
                                            <th>Cena</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($venue->sectors as $sector)
                                            @php
                                                $eventSector = $event->sectors->firstWhere('id', $sector->id);
                                            @endphp
                                            <tr>
                                                <td>{{ $sector->name }}</td>
                                                <td>{{ $sector->rows }}</td>
                                                <td>{{ $sector->columns }}</td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control"
                                                           name="sectors[{{ $sector->id }}][price]"
                                                           placeholder="Podaj cenę"
                                                           value="{{ old('sectors.' . $sector->id . '.price', $eventSector ? $eventSector->pivot->price : '') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="main-button-style">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skrypt do dynamicznego wyświetlania sektorów -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const venueSelect = document.getElementById('venue_id');
        const sectorContainers = document.querySelectorAll('.sectors-list');

        function toggleSectorDisplay(venueId) {
            sectorContainers.forEach(container => {
                container.style.display = 'none';
            });

            if (venueId) {
                const selectedContainer = document.querySelector(`.sectors-list[data-venue-id="${venueId}"]`);
                if (selectedContainer) {
                    selectedContainer.style.display = 'block';
                }
            }
        }

        // Inicjalizacja - pokaż sektory dla aktualnie wybranej sali
        toggleSectorDisplay(venueSelect.value);

        venueSelect.addEventListener('change', function () {
            toggleSectorDisplay(this.value);
        });
    });
</script>
@endsection
