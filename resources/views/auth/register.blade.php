@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Rejestracja') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Checkbox do wyboru organizatora -->
                            <div class="row mb-3">
                                <label for="organizerForm" class="col-md-4 col-form-label text-md-end">{{ __('Chcę założyć konto organizatora') }}</label>

                                <div class="col-md-6">
                                    <input 
                                        id="organizerForm" 
                                        type="checkbox" 
                                        class="form-control" 
                                        name="organizerForm"
                                    >
                                </div>
                            </div>

                            <!-- Formularz użytkownika -->
                            <div class="row mb-3 user-field">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Imię') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 user-field">
                                <label for="surname" class="col-md-4 col-form-label text-md-end">{{ __('Nazwisko') }}</label>

                                <div class="col-md-6">
                                    <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname">

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Formularz organizatora (pola ukryte domyślnie) -->
                            <div class="row mb-3 organizer-field" style="display: none;">
                                <label for="companyName" class="col-md-4 col-form-label text-md-end">{{ __('Nazwa Firmy') }}</label>

                                <div class="col-md-6">
                                    <input id="companyName" type="text" class="form-control @error('companyName') is-invalid @enderror" name="companyName" value="{{ old('companyName') }}" required autocomplete="companyName">

                                    @error('companyName')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Hasło') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Powtórz hasło') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Zarejestruj') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('organizerForm');
        const organizerFields = document.querySelectorAll('.organizer-field');
        const userFields = document.querySelectorAll('.user-field');

        // Funkcja do ukrywania/pokazywania pól formularza
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                organizerFields.forEach(field => field.style.display = 'block');
                userFields.forEach(field => field.style.display = 'none');
            } else {
                organizerFields.forEach(field => field.style.display = 'none');
                userFields.forEach(field => field.style.display = 'block');
            }
        });

        // Inicjalizacja stanu checkboxa przy załadowaniu strony
        if (checkbox.checked) {
            organizerFields.forEach(field => field.style.display = 'block');
            userFields.forEach(field => field.style.display = 'none');
        } else {
            organizerFields.forEach(field => field.style.display = 'none');
            userFields.forEach(field => field.style.display = 'block');
        }
    });
</script>
