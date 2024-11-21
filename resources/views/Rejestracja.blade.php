<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
    </head>

    <body>
        <form action="{{ url('/rejestracja') }}" method="post">
            @csrf
            <label for="name">Imię:</label>
            <input type="text" name="imie" id="imie" value="{{ old('imie') }}" required>
            <br>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            <br>

            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>
            <br>

            <label for="password_confirmation">Powtórz hasło:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <br>

            <button type="submit">Zarejestruj się</button>
        </form>
    </body>

</html>
