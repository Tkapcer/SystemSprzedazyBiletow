@extends('layouts.app')

@section('content')
<div class="container event-form">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="section-title">{{ __('Dodawanie wydarzenia') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                        <form action="{{ route('organizer.storeEvent') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Tytuł</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Opis</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Data i godzina wydarzenia</label>
                                <input type="datetime-local" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Zdjęcie</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="venue_id" class="form-label">Sala</label>
                                <select class="form-control @error('venue_id') is-invalid @enderror" id="venue_id" name="venue_id" required>
                                    <option value="">-- Wybierz salę --</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('venue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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
                                                <tr>
                                                    <td>{{ $sector->name }}</td>
                                                    <td>{{ $sector->rows }}</td>
                                                    <td>{{ $sector->columns }}</td>
                                                    <td>
                                                        <input
                                                            type="number"
                                                            class="form-control"
                                                            name="sectors[{{ $sector->id }}][price]"
                                                            step="0.01"
                                                            placeholder="Podaj cenę"
                                                        >
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="main-button-style">Dodaj wydarzenie</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const venueSelect = document.getElementById('venue_id');
        const sectorContainers = document.querySelectorAll('.sectors-list');

        venueSelect.addEventListener('change', function () {
            const selectedVenueId = this.value;

            // Ukryj wszystkie kontenery sektorów
            sectorContainers.forEach(container => {
                container.style.display = 'none';
            });

            // Pokaż kontener odpowiadający wybranej sali
            if (selectedVenueId) {
                const selectedContainer = document.querySelector(`.sectors-list[data-venue-id="${selectedVenueId}"]`);
                if (selectedContainer) {
                    selectedContainer.style.display = 'block';
                }
            }
        });
    });
</script>
@endsection
