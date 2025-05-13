@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h1>Czekasz w kolejce...</h1>
        <p>Liczba osób przed Tobą: <strong>{{ $position - 1 }}</strong></p>
        <p>Proszę czekać, aż poprzedni użytkownik zakończy pracę.</p>
    </div>

    <script>
        // Czas odpytywania (np. co 5 sekund)
        const pollingInterval = 5000;

        // Funkcja sprawdzająca status użytkownika
        function checkQueueStatus() {
            fetch('{{ route('queue.check') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.canEnter) {
                        // Jeśli użytkownik może wejść, przekieruj go
                        window.location.href = "{{ route('home') }}";
                    } else {
                        console.log('Użytkownik nadal czeka w kolejce...');
                    }
                })
                .catch(error => {
                    console.error('Błąd podczas sprawdzania statusu kolejki:', error);
                });
        }

        // Uruchom odpytywanie co określony czas
        setInterval(checkQueueStatus, pollingInterval);
    </script>

@endsection
