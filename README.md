# Pogodynka PAW1 LAB E

LAB E dodaje logowanie formularzem, wylogowanie, uzytkownikow Doctrine i hierarchie rol.
Kazda akcja kontrolerow Location i Measurement ma osobna role czynnosci.
ROLE_USER pozwala czytac listy i szczegoly, a ROLE_ADMIN dziedziczy odczyt i dodaje
tworzenie, edycje, potwierdzenie kasowania oraz kasowanie. Twig sprawdza uprawnienia linkow.
Hasla sa hashowane, formularz logowania i kasowanie korzystaja z CSRF.

Po utworzeniu schematu dodaj lokalne konta cwiczeniowe:
`php bin/console app:seed-users --password="LabE57754!"`.
Loginy: `admin`, `Jakub Rossa` (ROLE_ADMIN) i `czytelnik` (ROLE_USER).
Komenda pomija istniejace konta, wiec nie duplikuje identyfikatorow i nie zmienia ich hasel.
Punkt wejscia: `http://localhost:57754/login`.

LAB D dodaje CRUD Location i Measurement, osobne grupy walidacji create/edit
w config/validator, formularze z walidacja backendu i Bootstrap 5.
Listy /location i /measurement sa dostepne z nawigacji na desktopie i telefonie.
Kasowanie wymaga potwierdzenia i poprawnego tokenu CSRF. Lokalizacja z pomiarami
nie moze zostac usunieta. Schemat z LAB C pozostaje bez zmian.

Projekt Jakuba Rossy, RJ57754, grupa 353. Symfony 7.4 i SQLite.

## Uruchomienie

```powershell
composer install
php bin/console doctrine:schema:update --force
php bin/console app:seed-weather
php -S 127.0.0.1:57754 -t public
```

Przykladowe strony: `http://localhost:57754/weather/Szczecin` i `http://localhost:57754/weather/Police`.

Baza powstaje w `var/data_dev.db`. Tabele `miejscowosc` i `dane_meteorologiczne` maja relacje 1:M. Klucze glowne `id_miejscowosci` i `id_wpisu` sa generowane automatycznie. W tabeli pomiarow `id_miejscowosci` jest kluczem obcym. Schemat w notacji Chena jest w `docs/pogodynka-erd.drawio`.

Pole kraju zostalo pominiete zgodnie z diagramem zadania. Wyszukiwanie uzywa nazwy miasta. Repozytorium pomiarow zwraca wpisy z datami pozniejszymi niz dzisiaj. Komenda z danymi przykladowymi moze byc uruchamiana ponownie bez tworzenia kolejnych kopii tych samych rekordow.

## Lab F

For an existing development database from Lab E, run `php bin/console doctrine:schema:update --force` once. This adds the country code column and assigns PL to the existing locations.

```powershell
php .\bin\console weather:location 1
php .\bin\console weather:location 2
php .\bin\console weather:city PL Szczecin
php .\bin\console weather:city PL Police
```
