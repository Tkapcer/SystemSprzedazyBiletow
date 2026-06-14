# Viva La Billet - System sprzedaży i rezerwacji biletów

### Informacje o projekcie
* **Typ projektu:** Projekt grupowy realizowany w ramach studiów.
* **Przedmioty akademickie:**
    * Projekt zainicjowany i zaprojektowany na przedmiocie **Inżynieria oprogramowania**.
    * Projekt rozwijany i rozbudowany na przedmiocie **Tworzenie aplikacji bazodanowych**.

---

## Opis projektu
**Viva La Billet** to system zaprojektowany w celu kompleksowej obsługi sprzedaży oraz rezerwacji biletów na wydarzenia kulturalne. Platforma oferuje intuicyjną i przyjazną interakcję zarówno dla klientów poszukujących biletów, jak i dla organizatorów zarządzających wydarzeniami.

### Kluczowe funkcjonalności:
* **Zarządzanie wydarzeniami:** Organizatorzy mają możliwość tworzenia i edytowania własnych wydarzeń. Mogą określić nazwę, opis, gatunek, lokalizację, datę i godzinę, a także przypisać dostępne bilety wraz z ich szczegółami (miejsce, strefa, cena).
* **Zakup i rezerwacja biletów:** Klienci mają możliwość wyboru miejsc, ich kupna lub rezerwacji na wybrane wydarzenia. Po zakupie lub rezerwacji system automatycznie zmniejsza liczbę dostępnych biletów.
* **Wbudowany portfel i bramka płatnicza:** Transakcje realizowane są przy użyciu środków zgromadzonych w wirtualnym saldzie klienta. Saldo jest doładowywane za pośrednictwem zewnętrznej bramki płatniczej Stripe.
* **Historia transakcji:** Użytkownicy mają dostęp do archiwum zakupionych i zarezerwowanych biletów, co pozwala na wygodne przeglądanie historii transakcji.
* **System kolejkowania (Poczekalnia):** W celu zapewnienia stabilności działania systemu w sytuacjach dużego obciążenia zaimplementowano mechanizm kolejki. Klienci próbujący uzyskać dostęp do systemu w momencie przeciążenia zostają automatycznie umieszczeni w poczekalni. System stopniowo wpuszcza użytkowników w zależności od zwolnienia miejsca.
* **System raportowania:** Narzędzie dedykowane dla organizatorów, umożliwiające analizowanie statystyk sprzedaży oraz generowanie raportów. Dane w raportach można filtrować i sortować według: wydarzeń, sal, minimalnego dochodu oraz zakresu dat.

---

## Architektura i Technologie

System został zaprojektowany z wykorzystaniem technologii zapewniających płynną integrację frontendu z backendem. Aplikacja bazuje na architektonicznym wzorcu **MVC (Model-View-Controller)**, co gwarantuje strukturalne rozdzielenie logiki biznesowej od warstwy prezentacji.

* **Backend:** Framework **Laravel**. Odpowiada za logikę biznesową, obsługę żądań HTTP, walidację danych, przekierowania oraz komunikację z bazami danych.
* **Frontend:** Biblioteka **Vue.js** wspierana przez klasyczne technologie **HTML5** i **CSS**, co zapewnia dynamiczne oraz interaktywne komponenty interfejsu użytkownika.
* **Bazy danych:**
    * **SQLite:** Główna baza danych odpowiedzialna za przechowywanie danych trwałe (użytkownicy, organizatorzy, wydarzenia, bilety, lokalizacje). Relacje opierają się na powiązaniach 1:N oraz N:M.
    * **MySQL (Silnik MEMORY):** Pamięciowa baza danych wykorzystywana dedykowanie do obsługi mechanizmu kolejki, umożliwiająca błyskawiczne operacje w pamięci operacyjnej na identyfikatorach użytkowników i ich pozycjach.

### Podział na warstwy i moduły:
1. **Warstwa dostępu do danych:** Odpowiada za logikę bazy SQLite i wykorzystuje *Eloquent ORM* do obsługi głównych encji.
2. **Warstwa prezentacji:** Zbudowana za pomocą szablonów *Blade* w Laravelu oraz komponentów Vue, wyświetla dane i obsługuje formularze dynamiczne.
3. **Warstwa logiki sterowania:** Kontrolery pośredniczące w wymianie danych pomiędzy modelem a widokiem.
4. **Moduły funkcjonalne:** Moduł uwierzytelniania i autoryzacji (sesje, ochrona zasobów), moduł kolejki, moduł zarządzania wydarzeniami i miejscami oraz moduł transakcji i bilansu (Stripe API, integracja z Chart.js do rysowania wykresów).

---

## Analiza wymagań

### Wymagania niefunkcjonalne
* **Wydajność:** System reaguje na akcje użytkownika nie później niż po upływie 1 sekundy.
* **Skalowalność:** Stabilna obsługa co najmniej 10 użytkowników jednocześnie (łącznie z oczekującymi w poczekalni).
* **Bezpieczeństwo:** Szyfrowanie haseł użytkowników oraz obsługa wyjątków i błędów za pomocą komunikatów zwrotnych.

### Analiza MoSCoW
* **MUST (Wymagane):** Przeglądanie dostępnych wydarzeń, wybór, zakup lub rezerwacja biletów, rejestracja i logowanie (klient/organizator), dynamiczna zmiana liczby biletów po transakcji.
* **SHOULD (Powinny być):** Logowanie administratora, historia zakupów na koncie klienta, płatności przez bramkę, edycja/usuwanie wydarzeń przez organizatora, zwrot biletów i środków, weryfikacja danych przez administratora.
* **COULD (Mogą być):** Wybór poszczególnych miejsc w sektorze, poczekalnia/kolejka przy obciążeniu, automatyczne usuwanie nieopłaconych rezerwacji, filtrowanie po gatunkach, system raportowania statystyk.
* **WON'T (Nie tym razem):** Tryb dzienny/nocny, udogodnienia dla niepełnosprawnych (powiększenie czcionki), dokładna fizyczna mapa lokacji, graficzne przedstawienie rozmieszczenia sektorów.

---

## Specyfikacja zewnętrzna i uruchomienie

### Wymagania programowe
Do poprawnego uruchomienia aplikacji niezbędne jest zainstalowanie poniższych środowisk:
* **XAMPP** (do obsługi serwera bazodanowego MySQL)
* **Composer** (menedżer pakietów PHP dla frameworka Laravel)
* **Node.js**

### Instrukcja
1. Uruchom moduł **MySQL** w środowisku **XAMPP**, aby zapewnić działanie bazy danych obsługującej kolejkę.
2. Otwórz terminal w ścieżce projektu.
3. Wprowadź i wykonaj poniższe polecenie w celu zainicjalizowania serwera aplikacji:
   ```composer run dev```
