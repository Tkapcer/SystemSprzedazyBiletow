<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Sprzedaży Biletów</title>
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- Nagłówek -->
    <header class="header">
        <h1>System Sprzedaży Biletów</h1>
        <nav>
            <a href="/login">Logowanie</a>
            <a href="/register">Rejestracja</a>
        </nav>
    </header>

    <!-- Sekcja główna z wydarzeniami -->
    <main class="main-container">
        <h2 class="section-title">Nadchodzące Wydarzenia</h2>

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Koncert Zespołu XYZ">
            <h3>Koncert Zespołu XYZ</h3>
            <p>Data: 20 grudnia 2024<br>Godzina: 19:00<br>Miejsce: Hala Widowiskowa</p>
            <a href="/event/1" class="btn-details">Zobacz szczegóły</a>
        </div>

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Spektakl Teatralny ABC">
            <h3>Spektakl Teatralny ABC</h3>
            <p>Data: 25 grudnia 2024<br>Godzina: 18:00<br>Miejsce: Teatr Miejski</p>
            <a href="/event/2" class="btn-details">Zobacz szczegóły</a>
        </div>

        <div class="event-card">
            <img src="https://via.placeholder.com/300x200" alt="Występ Kabaretu 123">
            <h3>Występ Kabaretu 123</h3>
            <p>Data: 30 grudnia 2024<br>Godzina: 20:00<br>Miejsce: Sala Koncertowa</p>
            <a href="/event/3" class="btn-details">Zobacz szczegóły</a>
        </div>
    </main>

</body>
</html>
