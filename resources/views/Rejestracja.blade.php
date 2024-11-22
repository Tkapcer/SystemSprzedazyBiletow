<!DOCTYPE html>
<html lang="pl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <!-- Form używany by użytkownik mógł wprowadzić swoje dane.
            Elementy które może mieć form:
                input - miejsce na wpisanie informacji lub wybranie danych opcji
                label - wyświetla zwykle jakieś przydatne info
                select - drop-down list, ma w sobie option
                textarea - multi-line text input (wielkość określana za pomocą: rows i cols)
                button - przycisk
                fieldset - do grupowania elementów, zawiera legend oraz inne elementy, np.: label czy input
                    legend - określa co grupuje fieldset
                datalist - określa listę elementów, które dla <input list="id datalisty"> wyświetlą się przy kliknięciu na pole do wpisywania
                output - wyświetla wynik jakiejś operacji
                    option - element, który może zostać wybrany np.: w liście
                    multiple - nie ma wartości, mówi, że można wybrać wiele opcji

            Rodzaje inputów: (domyślny: text)
                button, checkbox, color, date, datetime-local, email, file, hidden (nie widać go xD), image, month, number, password,
                radio (wybór jednego z listy), range, reset, search, submit, tel (nr telefonu), text, time, url, week

            Atrybuty:
                action - definiuje akcję do wykonania, gdy wciśnięty zostanie submit, zwykle jest to przejście do innej strony
                target - sposób otwierania nowego okna, np.: _blank (nowa karta), _self (aktualna karta) jest domyślne
                method - domyślne get (przekazane przez URL), post (przekazane przez HTTP)
                autocomplete - on albo off
                nonvalidate - wpisuje się bez wartości (np.: <form action="coś" nonvalidate>), jeśli zostanie dodany, to wprowadzane dane nie będą sprawdzane pod kątem poprawności
                rel - relation
                name - nazwa
                enctype - mówi jak mają być kodowane dane, tylko dla method="post"
                accept-charset - mówi jakie kodowanie znaków przyjmować przy wpisywaniu danych float:left; width:40%; padding:50px;
        -->
        <div style="display:grid; grid-template-columns: auto auto; justify-content: space-evenly;">
            <div>
                <form action="{{ url('/rejestracja') }}" method="post">
                    <!-- @csrf   <- po co to? -->
                    <fieldset>
                        <legend>Dane osobowe:</legend>
                        <label for="imie">Imię i nazwisko:</label>
                        <br>
                        <input type="text" name="imie" id="imie" value="{{ old('imie') }}" required>
                        <br>

                        <label for="email">E-mail:</label>
                        <br>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        <br>

                        <label for="password">Hasło:</label>
                        <br>
                        <input type="password" name="password" id="password" required>
                        <br>

                        <label for="password_confirmation">Powtórz hasło:</label>
                        <br>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <br>
                        <br>

                        <button type="submit">Zarejestruj się</button>
                    </fieldset>
                </form>
            </div>

            <div>
                <form action="{{ url('/rejestracja') }}" method="post">
                    <!-- @csrf   <- po co to? -->
                    <fieldset>
                        <legend>Dane firmy:</legend>
                        <label for="nazwa">Nazwa firmy:</label>
                        <br>
                        <input type="text" name="nazwa" id="nazwa" value="{{ old('nazwa') }}" required>
                        <br>

                        <label for="email">E-mail:</label>
                        <br>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        <br>

                        <label for="password">Hasło:</label>
                        <br>
                        <input type="password" name="password" id="password" required>
                        <br>

                        <label for="password_confirmation">Powtórz hasło:</label>
                        <br>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <br>
                        <br>

                        <button type="submit">Zarejestruj się</button>
                    </fieldset>
                </form>
            </div>
        </div>

    </body>

</html>
