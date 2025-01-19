@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Zakup biletu - {{ $event->name }}</h1>

        <!-- Formularz do zakupu/rezerwacji biletu -->
        <form action="{{ route('ticket.store') }}" method="POST">
            @csrf
            <!-- Wybór sektora -->
            <div class="form-group">
                <label for="sector_id">Wybierz sektor:</label>
                <select name="sector_id" id="sector_id" class="form-control" required>
                    @foreach($sectors as $sector)
                        <option value="{{ $sector->id }}">
                            {{ $sector->name }} (Miejsca: {{ $sector->seats }}, Cena: {{ $sector->price }} zł)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_seats" id="number_of_seats" class="form-label">Wybierz liczbę miejsc</label>
                <input type="number" class="form-control" id="number_of_seats" name="number_of_seats" value="1" required>
            </div>

            <!-- Ukryte pole na event_id -->
{{--            <input type="hidden" name="event_id" value="{{ $event->id }}">--}}

            <!-- Dwa przyciski do wyboru akcji -->
            <button type="submit" name="status" value="purchased" class="btn btn-success mt-3">
                Kup bilet
            </button>
            <button type="submit" name="status" value="reserved" class="btn btn-warning mt-3">
                Zarezerwuj bilet
            </button>
        </form>
    </div>
@endsection
