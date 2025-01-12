@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Organizer Dashboard') }}</div>

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

                    {{--<form action="{{ route('organizer.createEvent') }}" method="POST">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                    </form>--}}
                        <div class="container">
                            <h1>Dodaj nowe wydarzenie</h1>
                            <form action="{{ route('organizer.storeEvent') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tytuł</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokalizacja</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Opis</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="event_date" class="form-label">Data i godzina wydarzenia</label>
                                    <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Zdjęcie</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sektory</label>
                                    <div id="sectors-container">
                                        <div class="sector-row mb-3">

{{--                                            To trzbe będzie przerobić na dynamiczne--}}
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="sectors[0][name]" placeholder="Nazwa sektora" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" name="sectors[0][seats]" placeholder="Liczba miejsc" min="1" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" name="sectors[0][price]" placeholder="Cena biletu" step="0.01" min="0" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="sectors[1][name]" placeholder="Nazwa sektora" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" name="sectors[1][seats]" placeholder="Liczba miejsc" min="1" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" name="sectors[1][price]" placeholder="Cena biletu" step="0.01" min="0" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Dodaj wydarzenie</button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
