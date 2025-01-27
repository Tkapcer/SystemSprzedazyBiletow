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

                    <form action="{{ route('addMoney') }}" method="POST">
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
                        <div style="text-align: center;">
                            <button type="submit" class="main-button-style btn-success" style="width: 15%;">
                                Dodaj środki
                            </button>
                        </div>
                    </form>

                    <br>
                    <h4 class="section-title">Twoje rezerwacje</h4>
                    @if($tickets->isEmpty() || $tickets->where('status', 'reserved')->isEmpty())
                        <p>Brak rezerwacji biletów do wyświetlenia.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Wydarzenie</th>
                                    <th style="width: 10%;">Sektor</th>
                                    <th style="width: 10%;">Liczba miejsc</th>
                                    <th style="width: 14%;">Status wydarzenia</th>
                                    <th style="width: 14%;">Dostępne akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    @if($ticket->status == 'reserved')
                                        <tr>
                                            <td>
                                                <a href="{{ route('event.show', $ticket->sector->event->id) }}" class="btn-admin-event" role="button">
                                                    {{ $ticket->sector->event->name }}
                                                </a>
                                            </td>
                                            <td>{{ $ticket->sector->name }}</td>
                                            <td>{{ $ticket->number_of_seats }}</td>
                                            <td>{{ $ticket->sector->event->status }}</td>
                                            <td>
                                                <div style="display: flex; gap: 10px;">
                                                    <form action="{{ route('ticket.pay', $ticket->id) }}" method="POST" style="flex: 1;">
                                                        @csrf
                                                        <button type="submit" class="main-button-style btn-success" style="width: 100%; text-align: center;">Opłać</button>
                                                    </form>
                                                    <form action="{{ route('ticket.cancel', $ticket->id) }}" method="POST" style="flex: 1;">
                                                        @csrf
                                                        <button type="submit" class="main-button-style-v2 btn-danger" style="width: 100%; text-align: center;">Anuluj</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <br>
                    <h4 class="section-title">Twoje bilety</h4>
                    @if($tickets->where('status', 'purchased')->isEmpty())
                        <p>Brak kupionych biletów do wyświetlenia.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Wydarzenie</th>
                                    <th style="width: 6%;">Sektor</th>
                                    <th style="width: 5%;">Liczba miejsc</th>
                                    <th style="width: 10%;">Status biletu</th>
                                    <th style="width: 8%;">Status wydarzenia</th>
                                    <th style="width: 10%;">Kod biletu</th>
                                    <th style="width: 9%;">Dostępne akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    @if($ticket->status == 'purchased')
                                        <tr>
                                            <td>
                                                <a href="{{ route('event.show', $ticket->sector->event->id) }}" class="btn-admin-event" role="button">
                                                    {{ $ticket->sector->event->name }}
                                                </a>
                                            </td>
                                            <td>{{ $ticket->sector->name }}</td>
                                            <td>{{ $ticket->number_of_seats }}</td>
                                            <td class="py-2 px-4">
                                                <span class="bg-green-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $ticket->status }}</span>
                                            </td>
                                            <td>{{ $ticket->sector->event->status }}</td>
                                            <td>{{ $ticket->code }}</td>
                                            <td style="width: 9%;">
                                                <form action="{{ route('ticket.return', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="main-button-style-v2 btn-danger">Zwróć</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
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
