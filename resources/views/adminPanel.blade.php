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

                        @if(!@empty($venues))
                            <div class="mt-6 border-t pt-4">
                                <h5 class="text-lg font-medium text-gray-700 mb-4">Istniejące obiekty:</h5>
                                <table class="table-auto border-collapse border border-gray-300 w-full text-left">
                                    <thead>
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2">Nazwa obiektu</th>
                                        <th class="border border-gray-300 px-4 py-2">Nazwa sektora</th>
                                        <th class="border border-gray-300 px-4 py-2">Liczba rzędów</th>
                                        <th class="border border-gray-300 px-4 py-2">Liczba kolumn</th>
                                        <th class="border border-gray-300 px-4 py-2 text-center">Akcje</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($venues as $venue)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2" rowspan="{{ $venue->sectors->count() > 0 ? $venue->sectors->count() : 1 }}">
                                                {{ $venue->name }}
                                            </td>

                                        @if($venue->sectors->isNotEmpty())
                                            @foreach($venue->sectors as $index => $sector)
                                                @if($index > 0)
                                                    <tr>
                                                        @endif

                                                        <td class="border border-gray-300 px-4 py-2">{{ $sector->name }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">{{ $sector->rows }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">{{ $sector->columns }}</td>

                                                        @if($index === 0)
                                                            <td class="border border-gray-300 px-4 py-2 text-center" rowspan="{{ $venue->sectors->count() }}">
                                                                <form action="{{ route('venues.destroy', $venue->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć ten obiekt?');">
                                                                    @csrf
                                                                    @if(!$venue->hasActiveEvent)
                                                                    @method('DELETE')
                                                                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Usuń</button>
                                                                    @endif
                                                                </form>
                                                            </td>
                                                        @endif

                                                        @if($index > 0)
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <td class="border border-gray-300 px-4 py-2 text-center" colspan="3">Brak sektorów</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <form action="{{ route('venues.destroy', $venue->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć ten obiekt?');">
                                                    @csrf
                                                    @if(!$venue->hasActiveEvent)
                                                        @method('DELETE')
                                                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Usuń</button>
                                                    @endif
                                                </form>
                                            </td>
                                            @endif
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Second card -->
<div class="container">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h4 class="section-title">{{ __('Gatunki') }}</h4>

        <!-- Formularz dodawania gatunku -->
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input
                    type="text"
                    name="name"
                    placeholder="Wprowadź nazwę gatunku"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="main-button-style btn-success w-65">
                    Dodaj gatunek
                </button>
            </div>
        </form>

        <!-- Lista dodanych kategorii -->
        @if($categories->count())
            <div class="mt-6 border-t pt-4">
                <h5 class="text-lg font-medium text-gray-700 mb-2">Istniejące gatunki:</h5>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    @foreach($categories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
