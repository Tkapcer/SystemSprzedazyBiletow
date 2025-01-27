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
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="Wprowadź kwotę" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Dodaj środki</button>
                        </form>

{{--                        @dump($errors)--}}

                    {{ __('You are logged in!') }}

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
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
                                    <th>Kod biletu</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->sector->event->name }}</td> {{--Dorobić tu przekierowanie na odpowiedni event--}}
                                        <td>{{ $ticket->sector->name }}</td>
                                        <td>{{ $ticket->number_of_seats }}</td>
                                        <td>{{ $ticket->sector->event->status }}</td>
                                        <td>{{ $ticket->code }}</td>

                                        @if($ticket->status == 'reserved')
                                            <th scope="row">
                                                <form action="{{ route('ticket.pay', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Opłać</button>
                                                </form>
                                            </th>
                                            <th scope="row">
                                                <form action="{{ route('ticket.cancel', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Anuluj</button>
                                                </form>
                                            </th>
                                        @endif

                                        @if($ticket->status == 'purchased')
                                            <th scope="row">
                                                <form action="{{ route('ticket.return', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Zwróć</button>
                                                </form>
                                            </th>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
