@extends('layouts.app')

@section('content')
    <div class="container-fluid mx-auto p-6">
        <div class="card shadow-sm rounded-lg">

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="text-center mb-6">
                    <a href="{{ route('createEvent') }}" class="main-button-style">
                        Dodaj nowe wydarzenie
                    </a>
                </div>

                <h2 class="section-title">Twoje wydarzenia</h2>

                @if ($events->isEmpty())
                    <p class="text-center text-gray-500">Nie masz jeszcze żadnych wydarzeń.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-sm text-gray-800">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="py-2 px-4 text-left w-1/12">Id</th>
                                    <th class="py-2 px-4 text-left w-2/12">Tytuł</th>
                                    <th class="py-2 px-4 text-left w-3/12">Data i godzina</th>
                                    <th class="py-2 px-4 text-left w-2/12">Lokalizacja</th>
                                    <th class="py-2 px-4 text-left w-2/12">Opcje</th>
                                    <th class="py-2 px-4 text-left w-2/12">Status</th>
                                 <!--   <th class="py-2 px-4 text-left w-2/12">Zakończ</th> -->
                                    <th class="py-2 px-4 text-left w-2/12">Usuń</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                        <td class="py-2 px-4">
                                        <a href="{{ route('event.show', $event->id) }}" class="text-blue-500 hover:text-blue-700">
                                        {{ $event->name }}
                                        </a></td>
                                        <td class="py-2 px-4">{{ $event->event_date->format('d.m.Y H:i') }}</td>
                                        <td class="py-2 px-4">{{ $event->location }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('editEvent', $event->id) }}" class="main-button-style">
                                                Edytuj
                                            </a>
                                        </td>
                                        <td class="py-2 px-4">
                                            @if ($event->status == 'approved')
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Zatwierdzone</span>
                                            @elseif ($event->status == 'waiting')
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Oczekujące</span>
                                            @elseif ($event->status == 'rejected')
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Odrzucone</span>
                                            @elseif ($event->status == 'expired')
                                                <span class="bg-grey-100 text-grey-800 px-3 py-1 rounded-full text-sm">Archiwalne</span>
                                            @endif
                                        </td>
                                       <!-- <td class="py-2 px-4">
                                            <a href="" class="main-button-style">
                                                Zakończ
                                            </a>
                                        </td> -->
                                        <td class="py-2 px-4">
                                            <a href="{{ route('cancelEvent', $event->id) }}" class="main-button-style-v2">
                                                Usuń
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
