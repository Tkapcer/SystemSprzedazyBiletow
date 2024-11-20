<!DOCTYPE html> <!-- Definiujemy, że kod jest napisany w HTML. --> <!-- Komentarze w takich cosiach. xD --> <!--  -->
<!-- Prawie każde rozpoczęcie znacznika musi mieć swój koniec i wewnętrzny element. Np.: dla <a> musi wystąpić </a> i coś pomiędzy. Są wyjątki, np.: <img>. -->
<!-- Rodzaje i znaczenie znaczników wraz z ich atrybutami:

        <h1> Tutaj wpisać tekst. </h1>  <h6> Tutaj wpisać tekst. </h6> - nagłówek(header): h1 jest najważniejsze i największe, h6 najmniej ważne zatem najmniesze
        <p> Tutaj napisać jakiś paragraf. </p> - paragraf
        <a href="link/adres strony"> Tutaj tekst, który po kliknięciu zabierze nas na stronę linku. </a> - link
            atrybut: href(URL do strony)
        <img src="obraz.jpg" alt="teskst opisujący zdj" width="liczba pikseli" height="liczba pikseli"> - służy do wyświetlania obrazów
            atrybuty: src(źródło), alt(tekst wyświetlany gdy obraz nie został poprawnie wczytany), width(szerokość), height(wysokość)
        <br> - nowa linia, umieszczany w pisanym tekście by rodzielić go wizualnie na stronie, nie ma elementu wewnątrz ani końca jak img!

     Atrybuty dodatkowe:
        style="wybrane:wartość;" - pozwala na edycję np.: koloru, czcionki czy rozmiau
            dla paragrafów czy nagłówków (tam gdzie jest jakiś tekst)
            wybrane i wartość są określane w CSS, przykładowe elementy do zmiany:
            background-color, color, font-size, font-family, text-align
        lang="skrót języka" - wyznacza język strony internetowej
            dla <html>
            (angielski Stany: "en-US", francuski: "fr", polski: "pl")
        title="nazwa" - dodatkowe info o elemencie
            dla paragrafów czy headerów
            pokaże się przy najechaniu myszką, aka: tooltip
        x
-->
<html lang="pl"> <!-- Początek kodu. -->
    <head> <!-- Nagłówek strony. -->
        Head
    </head>

    <body> <!-- Ciało stronki. -->

        <h1 title="Tooltip">Body: Najedź myszką.</h1>
        <p>Losowy tekst, który wpisywałam chcąc jakkolwiek zapełnić tą pustą przestrzeń jakimś długim, aczkolwiek nic zbytnio nie znaczącym tekstem...</p>

    </body>
</html> 
