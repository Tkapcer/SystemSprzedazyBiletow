@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container">
        <!-- First card -->
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
                                <th>Nazwa firmy</th>
                                <th>E-Mail</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organizers as $organizer)
                                <tr>
                                    <td scope="row">{{ $organizer->companyName }}</td>
                                    <td scope="row">
                                        <a href="mailto:{{ $organizer->email }}"> {{ $organizer->email }} </a>
                                    </td>
                                    <td>
                                        @if($organizer->status == 'waiting') Oczekujący @endif
                                        @if($organizer->status == 'approved') Potwierdzony @endif
                                        @if($organizer->status == 'rejected') Odrzucony @endif
                                    </td>
                                    <td>
                                        @if($organizer->status == 'waiting' || $organizer->status == 'rejected')
                                            <form action="{{ route('admin.confirm', $organizer->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style btn-success">Potwierdź</button>
                                            </form>
                                        @endif
                                        @if($organizer->status == 'approved' || $organizer->status == 'waiting')
                                            <form action="{{ route('admin.reject', $organizer->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style-v2 btn-danger">Odrzuć</button>
                                            </form>
                                        @endif
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
        <!-- Second card -->
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
                                <th>Tytuł wydarzenia <br> <div style="font-size: 50%">(Kliknij aby zobaczyć szczegóły) </div> </th>
                                <th>Status</th>
                                <th>Akcje</th>
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
                                        @if($event->status == 'waiting') Oczekujący @endif
                                        @if($event->status == 'approved') Potwierdzony @endif
                                        @if($event->status == 'rejected') Odrzucony @endif
                                    </td>
                                    <td>
                                        @if($event->status == 'waiting' || $event->status == 'rejected')
                                            <form action="{{ route('admin.approveEvent', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style btn-success">Potwierdź</button>
                                            </form>
                                        @endif
                                        @if($event->status == 'approved' || $event->status == 'waiting')
                                            <form action="{{ route('admin.rejectEvent', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="main-button-style-v2 btn-danger">Odrzuć</button>
                                            </form>
                                        @endif
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
