@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="section-title">{{ __('Rejestracja') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

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

                            <!-- Pola dla Klienta -->
                            <div class="row mb-3 user-field">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Imię') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name">
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
                                    <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" autocomplete="surname">
                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pola dla Organizatora -->
                            <div class="row mb-3 organizer-field" style="display: none;">
                                <label for="companyName" class="col-md-4 col-form-label text-md-end">{{ __('Nazwa Firmy') }}</label>
                                <div class="col-md-6">
                                    <input id="companyName" type="text" class="form-control @error('companyName') is-invalid @enderror" name="companyName" value="{{ old('companyName') }}" autocomplete="companyName">
                                    @error('companyName')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pola wspólne -->
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
                                    <button type="submit" class="main-button-style  btn-primary">
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
        const organizerCheckbox = document.getElementById('organizerForm'); // Checkbox dla organizatora
        const organizerFields = document.querySelectorAll('.organizer-field'); // Pola organizatora
        const userFields = document.querySelectorAll('.user-field'); // Pola klienta

        // Funkcja do pokazywania/ukrywania pól
        function toggleFields() {
            if (organizerCheckbox.checked) {
                organizerFields.forEach(field => field.style.display = 'block'); // Pokaż pola organizatora
                userFields.forEach(field => field.style.display = 'none'); // Ukryj pola klienta
            } else {
                userFields.forEach(field => field.style.display = 'block'); // Pokaż pola klienta
                organizerFields.forEach(field => field.style.display = 'none'); // Ukryj pola organizatora
            }
        }

        // Obsługa zmiany checkboxa
        organizerCheckbox.addEventListener('change', toggleFields);

        // Inicjalizacja przy załadowaniu strony
        toggleFields();

        // Usuwanie niepotrzebnych pól z wysyłanego formularza
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            if (organizerCheckbox.checked) {
                // Usuń pola klienta
                userFields.forEach(field => {
                    const input = field.querySelector('input');
                    if (input) input.disabled = true; // Oznacz pola klienta jako nieaktywne
                });
            } else {
                // Usuń pola organizatora
                organizerFields.forEach(field => {
                    const input = field.querySelector('input');
                    if (input) input.disabled = true; // Oznacz pola organizatora jako nieaktywne
                });
            }
        });
    });
</script>

