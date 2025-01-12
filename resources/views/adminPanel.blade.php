@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table>
                        <thead>
                        <tr>
                            <th scope="col">companyName</th>
                            <th scope="col">email</th>
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
                                                <button type="submit" class="btn btn-success">Confirm</button>
                                            </form>
                                        </th>
                                    @endif

                                    @if ($organizer->status == 'approved' || $organizer->status == 'waiting')
                                        <th scope="row">
                                            <form action="{{ route('admin.reject', $organizer->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form>
                                        </th>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table>
                        <thead>
                        <tr>
                            <th scope="col">Tytuł wydarzenia</th>
{{--                            Można więcej, w zmiennej jest przekazane wszystko z bazy
                                Jeszcze się można zastanowić czy po naciśnięciu też będzie przenosiło na stronę wydarzenai
                                czy wszysto będzie tu--}}
                        </tr>
                        @foreach($events as $event)
                            <th scope="row">{{ $event->name }}</th>

                            @if($event->status == 'waiting' || $event->status == 'rejected')
                                <th scope="row">
                                <form action="{{ route('admin.approveEvent', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Confirm</button>
                                    </form>
                                </th>
                            @endif

                            @if($event->status == 'approved' || $event->status == 'waiting')
                                <th scope="row">
                                    <form action="{{ route('admin.rejectEvent', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </form>
                                </th>
                            @endif

                        @endforeach
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
