@extends('layouts.app')

@section('content')
<div class="container event-form">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dodawanie wydarzenia') }}</div>

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

                    <div class="container">
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
                                <label for="location" class="form-label">Lokalizacja</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required>
                                @error('location')
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
                                <label class="form-label">Sektory</label>
                                <div id="sectors-container">
                                    @php
                                        $oldSectors = old('sectors', []);
                                    @endphp
                                    @foreach ($oldSectors as $index => $sector)
                                        <div class="sector-row mb-3" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control @error("sectors.$index.name") is-invalid @enderror" name="sectors[{{ $index }}][name]" placeholder="Nazwa sektora" value="{{ $sector['name'] ?? '' }}" required>
                                                    @error("sectors.$index.name")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control @error("sectors.$index.seats") is-invalid @enderror" name="sectors[{{ $index }}][seats]" placeholder="Liczba miejsc" min="1" value="{{ $sector['seats'] ?? '' }}" required>
                                                    @error("sectors.$index.seats")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control @error("sectors.$index.price") is-invalid @enderror" name="sectors[{{ $index }}][price]" placeholder="Cena biletu" step="0.01" min="0" value="{{ $sector['price'] ?? '' }}" required>
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

                            <button type="submit" class="btn btn-primary">Dodaj wydarzenie</button>
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
                                sectorRow.setAttribute('data-index', index);
                                sectorRow.innerHTML = `
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="sectors[${index}][name]" placeholder="Nazwa sektora" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" name="sectors[${index}][seats]" placeholder="Liczba miejsc" min="1" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" name="sectors[${index}][price]" placeholder="Cena biletu" step="0.01" min="0" required>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
