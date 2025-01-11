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
                            @foreach($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->companyName }}</th>
                                    <th scope="row">{{ $user->email }}</th>
                                    <th scope="row">{{ $user->organizerStatus }}</th>

                                    @if($user->organizerStatus == 'waiting' || $user->organizerStatus == 'rejected')
                                        <th scope="row">
                                            <form action="{{ route('admin.confirm', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Confirm</button>
                                            </form>
                                        </th>
                                    @endif

                                    @if ($user->organizerStatus == 'confirmed' || $user->organizerStatus == 'waiting')
                                        <th scope="row">
                                            <form action="{{ route('admin.reject', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form>
                                        </th>
                                    @endif
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
