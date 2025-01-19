@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Organizer Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h2>Twoje wydarzenia</h2>

                        @if ($events->isEmpty())
                            <p>Nie masz jeszcze żadnych wydarzeń.</p>
                        @else
                            <table class="table table-striped mt-3">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tytuł</th>
                                    <th>Data i godzina</th>
                                    <th>Lokalizacja</th>
                                    <th>Opcje</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->event_date->format('d.m.Y H:i') }}</td>
                                        <td>{{ $event->location }}</td>
                                        <td>
                                            <a href="{{ route('editEvent', $event->id) }}" class="btn btn-sm btn-warning">Edytuj</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif

                        <a href="{{ route('createEvent') }}" class="btn btn-primary mt-3">Dodaj nowe wydarzenie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
