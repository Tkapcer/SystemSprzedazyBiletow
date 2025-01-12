<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
</head>
<body>

    <!-- Sekcja nagłówka -->
    <header class="header">
        <h1>System Sprzedaży Biletów</h1>
        <nav>
            <a href="/">Wstecz</a>
            <a href="/login">Logowanie</a>
        </nav>
    </header>

    <!-- Sekcja formularza rejestracji -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div id="form" class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="organizerForm" class="col-md-4 col-form-label text-md-end">{{ __('Chcę założyć konto organizatora') }}</label> {{--Tak wgl to te __ w tym miejscy są potrzebne do obsługi wielojęzykowości ale teraz to i taj=k już to posułem xd więc można je z czasem olać i pisać wgl bez tych __()--}}

                                <div class="col-md-6">
                                    <input id="organizerForm" type="checkbox" class="form-control" name="organizerForm" v-model="customizeForm">
                                </div>
                            </div>
                            {{--
                                Trzeba to jakoś ładnie ułożyć później
                                Chyba najelpiej żeby chcekbos był po lewej przed napisem
                            --}}
                            <div v-if="!customizeForm" class="row mb-3">
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

                            <div v-if="!customizeForm" class="row mb-3">
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

                            <div v-if="customizeForm" class="row mb-3">
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
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        new Vue({
            el: '#form',
            data: {
                customizeForm: {{ old('organizerForm') ? 'true' : 'false' }}
            }
        });
    </script>
</body>
</html>
