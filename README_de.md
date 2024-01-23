# Terminbucher

[Englisch Dokumentation](./README.md)

Der Terminplaner basiert auf [Laravel](https://laravel.com/docs) und [Filament](https://filamentphp.com/docs).
Laravel ist das PHP-Framework und Filament ist ein Admin-Panel-Builder für die schnelle Anwendungsentwicklung. Sie sollten sich
einen Moment Zeit nehmen, um sich mit der [Verzeichnisstruktur](https://laravel.com/docs/10.x/structure) einer Laravel
Anwendung vertraut machen.

1. `php composer install` - Installiert Abhängigkeiten
2. `sail up -d` - Führt docker compose und andere Laravel-Funktionen aus
3. `sail artisan migrate:fresh` - Installiert db Migrationen
4. `sail artisan db:seed` - Legt die Datenbank mit Testdaten an
5. Sie können auf das lokale System zugreifen, indem Sie zu http://laravel.test navigieren.

In der Datei `.env` sehen Sie die `SQS_QUEUE`. Hierher werden die Termine verschoben, wenn sie erstellt werden. Die
Struktur des Arrays von Objekten folgt.


```json
[
    {
        "id": 26,
        "description": "<ul><li>Did you purchase your bike from Mount7?</li><ul><li>Yes</li></ul><li>Serial Number</li><ul><li>test</li></ul><li>What is wrong with the bike?</li><ul><li>test</li></ul></ul>",
        "start": "2024-01-30 23:00:00",
        "end": "2024-01-30 23:00:00",
        "status": "upcoming",
        "contact_id": 57,
        "user_id": 1,
        "service_id": 1,
        "created_at": "2023-12-15T15:01:34.000000Z",
        "updated_at": "2023-12-15T15:01:34.000000Z",
        "service": {
            "id": 1,
            "name": "Workshop Appointment",
            "description": "<strong>We will repair all bikes!</strong><p>Except fixies</p>",
            "duration": 1,
            "all_day": 1,
            "minimum_cancel_hours": 2,
            "allow_user_selection": 0,
            "terms": "<p>We are committed to your privacy. If you'd like to read our <a href=\"https://google.com/\"><span style=\"text-decoration: underline;\">privacy policy</span></a> or <a href=\"https://google.com/\"><span style=\"text-decoration: underline;\">terms</span></a> they are linked here.</p>",
            "active": true,
            "created_at": null,
            "updated_at": "2023-12-14T11:01:25.000000Z"
        },
        "user": {
            "id": 1,
            "name": "Kailey Johns",
            "email": "info@mount7.com",
            "email_verified_at": "2000-10-01T00:00:00.000000Z",
            "picture": null,
            "maximum_appointments_per_day": 1,
            "locale": "en",
            "created_at": "2023-12-14T11:00:50.000000Z",
            "updated_at": "2023-12-14T11:00:50.000000Z"
        },
        "contact": {
            "id": 57,
            "email": "hello@example.com",
            "name": "Mister Mister",
            "phone": "myphone",
            "created_at": "2023-12-15T15:01:34.000000Z",
            "updated_at": "2023-12-15T15:01:34.000000Z"
        }
    }
]

```

## Deployment

### Bündelung von Frontend-Assets

Wenn Sie das Frontend ändern, müssen Sie `sail npm run dev` ausführen, um die Assets on the fly zu erstellen. Sie können auch
sail npm run build` ausführen, um die Assets für die Umgebung zu erstellen. Laravel spricht mehr über
[asset bundling](https://laravel.com/docs/10.x/vite#main-content.

### Unit-Tests

Unit-Tests werden mit [PEST](https://pestphp.com/) geschrieben. Die statische Analyse wird mit [PHPStan](https://phpstan.org/) durchgeführt.
Sie können beide Befehle im Projekt innerhalb von `sail` mit dem Befehl `sail php ./vendor/bin/phpstan &&
sail php ./vendor/bin/pest`.

Die Datenbank sollte mit `sail artisan db:seed` gefüllt werden, bevor die Unit-Tests ausgeführt werden, da PEST diese für
Tests benötigt.

### Code-Stil und Leitfaden

Das Code-Styling sollte dem Laravel-Code-Style folgen, für den die meisten IDEs eine Einstellung haben. Pint ist so konfiguriert, dass es auf Builds läuft und
Sie sollten `sail php ./vendor/bin/pint` vor dem Pushen ausführen, um das Code-Styling zu korrigieren und durchzusetzen. Sie können auch den gleichen
Befehl mit `sail php ./vendor/bin/pint --test` ausführen, um zu sehen, ob alle Dateien das Code-Styling einhalten.

PEST ist so konfiguriert
strict types](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict) durchzusetzen.

Jede PHP-Datei in der Anwendung, mit Ausnahme der Abhängigkeiten, sollte mit

```php
<?php

declare(strict_types=1);
```

### Bitbucket Pipelines
[Pipelines](https://support.atlassian.com/bitbucket-cloud/docs/use-pipes-in-bitbucket-pipelines/) läuft auf Pushesan `master`.Die Pushes werden durch einen Pull Request durchgeführt und führen die Befehle in `./bitbucket-pipelines.yml` aus. Sie
können den Status der Pipeline auf [bitbucket.org](https://bitbucket.org/mount7freiburg/termin.mount7.com/pipelines) einsehen. Das
Bitbucket Repository benötigt die folgenden Variablen, um richtig zu funktionieren. Diese befinden sich unter
_Repository-Einstellungen -> Repository-Variablen_

| Repository Variable | Setup in Bitbucket        | Description                                                                                                              |
|---------------------|---------------------------|--------------------------------------------------------------------------------------------------------------------------|
| `USER`              | mount7                    | Bitbucket benutzt diesen Benutzer um SSH und SCP Befehle auszuführen.                                                    |
| `BUILD_PATH`        | staging.termin.mount7.com | Der relative Pfad zum Ablegen der ZIP-Datei in `/var/builds/$BUILD_PATH`, der in `bitbucket-pipelines.yml` definiert ist |
| `SERVER`            | staging.termin.mount7.com | Server, den Bitbucket zum Senden der ZIP-Datei und zum Ausführen von Befehlen verwendet                                  |                                
| `SSH_PORT`          | 9963                      | Port, der für SCP- und SSH-Befehle verwendet wird                                                                        |

Wenn die Pipeline fehlschlägt, können Sie lokal testen:

```shell
docker build --memory=1g --memory-swap=1g -t chris/bbtermin:tag -f my.dockerfile .
docker run --network=host --name bbmysql -e MYSQL_DATABASE='laravel' -e MYSQL_ROOT_PASSWORD='password' -e MYSQL_ROOT_HOST='%' -e MYSQL_USER='sail' -e MYSQL_PASSWORD='password' -d mysql:8.0
docker run -it --network=host --memory=4g --memory-swap=4g --memory-swappiness=0 --cpus=4 --entrypoint=/bin/bash chris/bbtermin:tag```
```

Wenn Sie eine Fehlermeldung erhalten, dass der Container für mysql verwendet wird, können Sie `docker rm container-name` ausführen und neu starten. 

Sie können auch die Fehlermeldung ignorieren, dass der Kernel keinen Speicher-Swappiness unterstützt
