@extends('layouts.app')

@section('content')
<div class="container event-form">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="section-title">{{ __('Dodaj nową lokalizację') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form action="{{ route('venues.store') }}" method="POST">
                        @csrf

                        <!-- Nazwa -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Miejsce</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Lokalizacja -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokalizacja</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Opis -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr>
                        <h5>Sektory</h5>
                        <div id="sectors">
                            <div class="sector border p-3 mb-3 rounded">
                                <label class="form-label">Nazwa sektora:</label>
                                <input type="text" name="sectors[0][name]" class="form-control mb-2" required>

                                <label class="form-label">Rzędy:</label>
                                <input type="number" name="sectors[0][rows]" class="form-control mb-2" required>

                                <label class="form-label">Kolumny:</label>
                                <input type="number" name="sectors[0][columns]" class="form-control mb-2" required>

                                <button type="button" class="main-button-style red-button remove-sector mt-2">Usuń sektor</button>
                            </div>
                        </div>

                        <button type="button" onclick="addSector()" class="main-button-style mt-2">Dodaj sektor</button>

                        <br><br>
                        <button type="submit" class="main-button-style">Zapisz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skrypt do dynamicznego dodawania i usuwania sektorów -->
<script>
    let sectorIndex = 1;

    function addSector() {
        const container = document.getElementById('sectors');
        const html = `
        <div class="sector border p-3 mb-3 rounded">
            <label class="form-label">Nazwa sektora:</label>
            <input type="text" name="sectors[${sectorIndex}][name]" class="form-control mb-2" required>

            <label class="form-label">Rzędy:</label>
            <input type="number" name="sectors[${sectorIndex}][rows]" class="form-control mb-2" required>

            <label class="form-label">Kolumny:</label>
            <input type="number" name="sectors[${sectorIndex}][columns]" class="form-control mb-2" required>

            <button type="button" class="main-button-style red-button remove-sector mt-2">Usuń sektor</button>
        </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        sectorIndex++;
    }

    // Obsługa przycisku "Usuń sektor"
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-sector')) {
            const sectorDiv = e.target.closest('.sector');
            if (sectorDiv) {
                sectorDiv.remove();
            }
        }
    });
</script>

@endsection
