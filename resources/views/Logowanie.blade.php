<form action="{{ url('/login') }}" method="POST">
    @csrf
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Hasło:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Zaloguj się</button>
</form>
