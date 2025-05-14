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

                        

                        <button type="submit" class="main-button-style">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
