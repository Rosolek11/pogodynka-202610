# Pogodynka PAW1 LAB C

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
