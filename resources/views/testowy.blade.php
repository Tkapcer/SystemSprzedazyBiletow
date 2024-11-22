<!DOCTYPE html> <!-- Definiujemy, że kod jest napisany w HTML. --> <!-- Komentarze w takich cosiach xD --> <!--  -->
<!-- Prawie każde rozpoczęcie znacznika musi mieć swój koniec i wewnętrzny element.
    Np.: dla <a> musi wystąpić </a> i coś pomiędzy. Są wyjątki, np.: <img>.
    Pisać znaczniki i atrybuty małymi literami!
-->
<!-- Rodzaje i znaczenie znaczników wraz z ich atrybutami:

        <h1> Tutaj wpisać tekst. </h1>  <h6> Tutaj wpisać tekst. </h6> - nagłówek(header): h1 jest najważniejsze, h6 najmniej ważne (warto używać by wyznaczyć strukturę strony)
        <p> Tutaj napisać jakiś paragraf. </p> - paragraf
        <a href="link/adres strony"> Tutaj tekst (lub zdj czy coś), który po kliknięciu zabierze nas na stronę linku. </a> - link
            atrybut: href(URL do strony) ; target
        <img src="obraz.jpg" alt="teskst opisujący zdj" width="liczba pikseli" height="liczba pikseli"> - służy do wyświetlania obrazów
            atrybuty: src(źródło), alt(tekst wyświetlany gdy obraz nie został poprawnie wczytany), width(szerokość), height(wysokość)
            zamiast jpg może być nawet gif
            zamiast width i height lepiej użyć: style="width:ileśpx;height:ileśpx;", ponieważ jest odporne na zmiany wprowadzane przez CSS external
        <br> - nowa linia, umieszczany w pisanym tekście by rodzielić go wizualnie na stronie, nie ma elementu wewnątrz ani końca jak img!
        <hr> - poziona linia
        <pre> Tekst z enterami itp. </pre> - wyznacza wcześniej sformatowany tekst, zachcowując jego białe znaki
        <b> Tekst do pogrubienia. </b> - pogrubienie tekstu
        <i> Tekst do pochylenia. </i> - kursywa (pochylenie tekstu)
        <cite> Tytuł </cite> - kursywa dla nazw własnych, tytułów itp.
        <em> Tekst do pochylenia i zaakcentowania. </em> - kursywa gdzie czytnik ekranu zaakcentuje ten tekst
        <sub> dół </sub> - index dolny
        <sup> góra </sup> - index górny
        <mark> Tekst gdzie użyto zakreślacza. </mark> - zakreślenie (markerem)
        <ins> Podreślony tekst. </ins> - podkreślenie
        <del> Przekreślony tekst. </del> - przekreślenie
        <q> Cytat. </q> - wstawia cydzysłowy wokół cytowanego tekstu
        <abbr title="Pełna nazwa"> Skrót. </abbr> - wskazuje, że coś jest skrótem
            używa atrybutu title do wyświetlenia pełnej nazwy po najechaniu myszką
        <button onclick="document.location='default.asp'"> Napis </button> - przycisk
            onclick (przekierowanie) - Javascript (chyba?)
        <table></table> - tabelki: <tr></tr> - rząd, <th></th> - nagłówek tabeli (wszystkie są zwykle w pierwszym rzędzie), <td></td> - dane
        <ul> elementy listy </ul> - lista wykropkowana
        <ol> elementy listy </ol> - lista wypunktowana
            <li> Coś </li> - element listy
        <dl><dt><dd>... - lista wylistowana czy jakoś tak

        <div> wnętrze </div> - sekcja (division), zwykle używana jako kontener
            można używać atrybutów np.: style, class, id
        <span> coś </span> - sekcja inline, która zawiera tak mało miejsca jak potrzebuje, w przeciwieństwie do <div> czy <p>, które zajmują cały obszar prawo-lewo jaki mogą


     Atrybuty dodatkowe: 
        style="wybrany parametr:wartość;" - pozwala na edycję np.: koloru, czcionki czy rozmiau - inline CSS
            dla paragrafów czy nagłówków (tam gdzie jest jakiś tekst czy kontener)
            wszystkie parametry i ich wartości są w cudzysłowie, rozdziela się je średnikami
            wybrane parametry i wartość są określane w CSS, przykładowe elementy do zmiany:
            background-color, color, font-size, font-family, text-align, border, padding, margin, float
        lang="skrót języka" - wyznacza język strony internetowej
            dla <html>
            (angielski Stany: "en-US", francuski: "fr", polski: "pl")
        title="nazwa" - dodatkowe info o elemencie
            dla paragrafów czy headerów
            pokaże się przy najechaniu myszką, aka: tooltip
        class - Klasy są przydatne gdy chcemy ustalić konkretny wygląd dla grupy obiektów(np kontenerów, czy paragrafów) na raz,
            używane są także przy dodawaniu Javascript do stronki, w CSS: .jakaś_klasa {zawartość style}
        id - jak klasy, ale dla tylko jednego elementu, w CSS: #jakieś-id {zawartość style}

     Dodatkowe info:
        wartości atrybutów umieszczać w cudzysłowach "" lub pojedynczych apostrofach ''
        jeśli chcemy wyświetlić cudzysłów to zewnętrzny zamienić na pojedynczy, np: 'To jest tutył "Tutuł", jej xD'
        aby używać external CSS, w <head> należy dołączyć: <link rel="stylesheet" href="styles.css">
        <style> określone CSS </style> - do pisania style internal (w pliku HTML)
        <sccript> Javascript </script> - do pisania kodu Javascriptu bezpośrednio w pliku HTML
        <nonscript> Tekst alternatywny. </nonscript> - wyświetla tekst alternatywny w przypadku braku możliwości wykonania skryptów
        href może wskazywać na id (daje to możliwość np przeskakiwania do danego rodziału)
-->
<!--
    Scieżki do plików:
        "pliczek.txt" - plik znajduje się w tym samym folderze co dana strona
        "ello/pliczek.txt" - plik znajduje się w folderze ello, który jest w folderze co dana strona
        "../pliczek.txt" - plik znajduje się jeden folder wyżej niż ten gdzie jest dana strona
        "/pliczek.txt" - znajduje się w root strony, czy jakoś tak
-->
<!-- Początek kodu. -->
<html lang="pl">
<!-- Nagłówek strony. -->
    <head>
        <!-- Nazwa strony wyświetlana jako nazwa karty. Tylko tekst. Musi występować w plikach HTML. -->
        <title>Strona testowa</title>
        <!-- "favicon" - To małe logo przy nazwie stronki w karcie.
                link może także służyć do podłączania plików CSS do HTML, np:
                <link rel="stylesheet" href="mystyle.css">
        -->
        <link rel="icon" type="image/x-icon" href="https://img.freepik.com/darmowe-wektory/abstrakcyjna-niebieski-okrag-na-przejrzystym-tle_1035-9079.jpg?t=st=1732141824~exp=1732145424~hmac=a9a6d1db6c17521d595025e94238de7d9c5577e117831fb58043625635fc1754&w=740">
        <!--
        W magłówku można umieścić także znacznik <meta>, ma takie atrybuty jak:
            charset - wyznacza kodowanie znaków, np: UTF-8
            name - dekalruje jakiś element + content - definiuje go, np:
                name="description" content="Nasza stronka"
                name="author" content="Imię Nazwisko"
            http-equiv="refresh" content="wartość" - wyznacza odświeżanie strony na co ileś sekund
            name="viewport" content="width=device-width, initial-scale=1.0" - sprawia, że strona wygląda dobrze na wszystkich urządzeniach
        -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <!-- Ciało stronki. -->
    <body>
    <!-- Kontener 1 -->
        <div style="width:400px;margin:auto;background:black;color:white;">
            <h1 title="Tooltip">Body: Najedź myszką.</h1>
        </div>
        <!-- Kontener 2 -->
        <div style="width:700px;margin:auto;background:lightgreen;">
            <p>Losowy tekst, który wpisywałam chcąc jakkolwiek zapełnić tą pustą przestrzeń jakimś długim, aczkolwiek nic zbytnio nie znaczącym tekstem...</p>
        </div>
    </body>
</html> 
<!-- CSS - Cascading Style Scheets -->
