@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-lg p-6 text-center max-w-md">
            <h1 class="text-2xl font-bold text-gray-800">Czekasz w kolejce...</h1>
            <p class="text-gray-600 mt-2">Liczba osób przed Tobą: 
                <span class="font-bold text-purple-500">{{ $position - 1 }}</span>
            </p>
            <p class="text-gray-600 mt-4">Proszę czekać, aż poprzedni użytkownik zakończy pracę.</p>

            <div class="mt-6">
                <div class="relative w-50 h-4 bg-gray-300 rounded-full overflow-hidden">
                    <div class="absolute top-0 left-0 h-full bg-green-500 animate-pulse transition-all duration-1000 ease-in-out animate-pulse" 
                         style="width: {{ 100 - (($position - 1) / 10 * 100) }}%;"></div>
                </div>
            </div>

            <p class="text-gray-500 text-sm mt-4">Strona odświeży się automatycznie.</p>
        </div>
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
