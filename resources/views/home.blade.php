@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="section-title">{{ __('Twoje konto') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <form action="{{ route('addMoney') }}" method="POST" class="p-3 bg-light rounded shadow-sm">
                            @csrf

                            <div class="mb-3">
                                <label for="amount" class="form-label">Kwota doładowania</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" placeholder="Wprowadź kwotę" required>
                                @error('amount')
                                     <span class="invalid-feedback" role="alert">
                                           <strong>{{ $message }}</strong>
                                     </span>
                                @enderror
                            </div>
                            @csrf
                            <button type="submit" class="main-button-style btn-success">Dodaj środki</button>
                        </form>

{{--                        @dump($errors)--}}

                    {{ __('') }}

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger invalid-feedback">
                                   @foreach ($errors->all() as $error)
                                       {{ $error }}
                                   @endforeach
                            </div>
                        @endif

                        @if($tickets->isEmpty())
                            <p>Brak biletów do wyświetlenia.</p>
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Nazwa wydarzenia</th>
                                    <th>Sektor</th>
                                    <th>Liczba miejsc</th>
                                    <th>Status</th>
                                    <th>Status wydarzenia</th>
                                    <th>Kod biletu</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $ticket)
                                @if($ticket->status == 'reserved')
                                    <tr>
                                        <td>{{ $ticket->sector->event->name }}</td> {{--Dorobić tu przekierowanie na odpowiedni event--}}
                                        <td>{{ $ticket->sector->name }}</td>
                                        <td>{{ $ticket->number_of_seats }}</td>
                                        <td class="py-2 px-4">
                                                <span class="bg-blue-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $ticket->status }}</span>
                                        </td>
                                        <td>{{ $ticket->sector->event->status }}</td>
                                        <td>{{ $ticket->code }}</td>
                                        
                                        <th scope="row">
                                            <form action="{{ route('ticket.pay', $ticket->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style btn-success">Opłać</button>
                                            </form>
                                        </th>
                                        <th scope="row">
                                            <form action="{{ route('ticket.cancel', $ticket->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style-v2 btn-danger">Anuluj</button>
                                            </form>
                                        </th>
                                        
                                    </tr>
                                @endif
                                @endforeach
                                @foreach($tickets as $ticket)
                                @if($ticket->status == 'purchased')
                                    <tr>
                                        <td>{{ $ticket->sector->event->name }}</td> {{--Dorobić tu przekierowanie na odpowiedni event--}}
                                        <td>{{ $ticket->sector->name }}</td>
                                        <td>{{ $ticket->number_of_seats }}</td>
                                        <td class="py-2 px-4">
                                                <span class="bg-green-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $ticket->status }}</span>
                                        </td>
                                        <td>{{ $ticket->sector->event->status }}</td>
                                        <td>{{ $ticket->code }}</td>

                                        <th scope="row">
                                            <form action="{{ route('ticket.return', $ticket->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style-v2 btn-danger">Zwróć</button>
                                            </form>
                                        </th>

                                    </tr>
                                @endif
                                @endforeach
                                <tr>     <td>                        {{--                        @dump($errors)--}} </td> </tr>
                                </tbody>
                            </table>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
