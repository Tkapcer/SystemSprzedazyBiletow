@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4>{{ __('Panel Organizatora') }}</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($status == 'waiting')
                        <div class="p-4 bg-blue-100 text-blue-900 rounded-lg mb-4">
                            <strong>Twoje konto oczekuje na weryfikację...</strong>
                        </div>
                    @elseif ($status == 'rejected')
                        <div class="p-4 bg-red-50 text-red-900 rounded-lg mb-4">
                            <strong>Odrzucono prośbę o utworzenie konta organizatora.</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
