<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>
    <div class="header">
        <h1>System Sprzedaży Biletów</h1>
        <a href="/">Strona główna</a>
    </div>

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Zaloguj się</button>
    </form>
</body>
</html>
