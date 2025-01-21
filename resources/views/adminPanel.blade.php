@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Panel administratora') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>
                        <a class="btn btn-primary" data-bs-toggle="collapse" href="#organizersSection" role="button" aria-expanded="false" aria-controls="organizersSection">
                            Organizatorzy (kliknij, aby rozwinąć)
                        </a>
                    </h5>
                    <div class="collapse" id="organizersSection">
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
                                        <th scope="row">{{ $organizer->companyName }}</th>
                                        <th scope="row">{{ $organizer->email }}</th>
                                        <th scope="row">{{ $organizer->status }}</th>

                                        @if($organizer->status == 'waiting' || $organizer->status == 'rejected')
                                            <th scope="row">
                                                <form action="{{ route('admin.confirm', $organizer->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Potwierdź</button>
                                                </form>
                                            </th>
                                        @endif

                                        @if ($organizer->status == 'approved' || $organizer->status == 'waiting')
                                            <th scope="row">
                                                <form action="{{ route('admin.reject', $organizer->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Odrzuć</button>
                                                </form>
                                            </th>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>
                        <a class="btn btn-primary" data-bs-toggle="collapse" href="#eventsSection" role="button" aria-expanded="false" aria-controls="eventsSection">
                            Wydarzenia (kliknij, aby rozwinąć)
                        </a>
                    </h5>
                    <div class="collapse" id="eventsSection">
                        <table class="table table-bordered mt-3">
                        <thead>
                        <tr>
                            <th>Tytuł wydarzenia</th>
                            <th>Status</th>
                            <th>Akcje</th>
                        </tr>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <a href="{{ route('event.show', $event->id) }}" class="btn btn-primary" role="button">
                                        {{ $event->name }}
                                    </a>
                                </td>
                                <td>{{ $event->status }}</td>
                                <td>
                                    @if($event->status == 'waiting' || $event->status == 'rejected')
                                        <form action="{{ route('admin.approveEvent', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Potwierdź</button>
                                        </form>
                                    @endif
                                    @if($event->status == 'approved' || $event->status == 'waiting')
                                        <form action="{{ route('admin.rejectEvent', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Odrzuć</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
