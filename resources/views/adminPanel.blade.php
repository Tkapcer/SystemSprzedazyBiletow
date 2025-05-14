@extends('layouts.app')

@section('content')
<div class="row">

    <!-- First card -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                    <h4 class="section-title">Miejsca</h4>

                        <div style="text-align: center;">
                            <button type="submit" class="main-button-style btn-success" style="width: 100%;">
                                <a href="{{ route('venues.create') }}" class="btn btn-primary mt-3">Dodaj nową lokalizacje sali</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second card -->
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="section-title">{{ __('Dodaj Gatunek') }}</h4>

                    <!-- Formularz dodawania gatunku -->
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                placeholder="Wprowadź nazwę gatunku"
                                value="{{ old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div style="text-align: center;">
                            <button type="submit" class="main-button-style btn-success" style="width: 100%;">
                                Dodaj gatunek
                            </button>
                        </div>
                    </form>

                    @foreach($categories as $category)
                        <div class="mt-3">
                            <span>{{ $category->name }}</span>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="container">
        <!-- Third card -->
        <div class="col-md-6">
            <div class="card">
                <div class="section-title">{{ __('Organizatorzy') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Nazwa firmy</th>
                                <th style="width: 25%;">E-Mail</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 18%;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organizers as $organizer)
                                <tr>
                                    <td>{{ $organizer->companyName }}</td>
                                    <td>
                                        <a href="mailto:{{ $organizer->email }}"> {{ $organizer->email }} </a>
                                    </td>
                                    <td>
                                        @if ($organizer->status == 'approved')
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Potwierdzony</span>
                                        @elseif ($organizer->status == 'rejected')
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Odrzucony</span>
                                        @elseif ($organizer->status == 'waiting')
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Oczekujący</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 10px; width: 110%; padding-right:10px;">
                                            @if($organizer->status == 'waiting' || $organizer->status == 'rejected')
                                                <form action="{{ route('admin.confirm', $organizer->id) }}" method="POST" style="flex: 1;">
                                                    @csrf
                                                    <button type="submit" class="main-button-style btn-success">Zatwierdź</button>
                                                </form>
                                            @endif
                                            @if($organizer->status == 'approved' || $organizer->status == 'waiting')
                                                <form action="{{ route('admin.reject', $organizer->id) }}" method="POST" style="flex: 1;">
                                                    @csrf
                                                    <button type="submit" class="main-button-style-v2 btn-danger">Odrzuć</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- Fourth card -->
        <div class="col-md-6">
            <div class="card">
                <div class="section-title">{{ __('Wydarzenia') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Tytuł wydarzenia</th>
                                <th style="width: 14%;">Status</th>
                                <th style="width: 18%;">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>
                                        <a href="{{ route('event.show', $event->id) }}" class="btn-admin-event" role="button">
                                            {{ $event->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($event->status == 'approved')
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Potwierdzone</span>
                                        @elseif ($event->status == 'rejected')
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Odrzucone</span>
                                        @elseif ($event->status == 'waiting')
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Oczekujące</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            @if($event->status == 'waiting' || $event->status == 'rejected')
                                                <form action="{{ route('admin.approveEvent', $event->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="main-button-style btn-success">Zatwierdź</button>
                                                </form>
                                            @endif
                                            @if($event->status == 'approved' || $event->status == 'waiting')
                                                <form action="{{ route('admin.rejectEvent', $event->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="main-button-style-v2 btn-danger">Odrzuć</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
