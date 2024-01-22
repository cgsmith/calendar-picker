# Appointment Booker

The appointment booker is based on [Laravel](https://laravel.com/docs) and [Filament](https://filamentphp.com/docs).
Laravel is the PHP framework and Filament is an admin panel builder for rapid application development. You should take
a moment to familiarize your self with the [directory structure](https://laravel.com/docs/10.x/structure) of a Laravel
application.

1. `php composer install` - Installs dependencies
2. `sail up -d` - Runs docker compose and other Laravel functions
3. `sail artisan migrate:fresh` - Installs db migrations
4. `sail artisan db:seed` - seeds database with test data

In the `.env` file you will see the `SQS_QUEUE`. This is where appointments will be pushed when they are created. The
structure of the array of objects follows.

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

### Frontend Asset Bundling

If you are changing the frontend you will need to run `sail npm run dev` to build assets on the fly. You may also run
`sail npm run build` to build assets for the environment. Laravel talks more about
[asset bundling](https://laravel.com/docs/10.x/vite#main-content.

### Unit Tests

Unit tests are written with [PEST](https://pestphp.com/). Static analysis is done with [PHPStan](https://phpstan.org/).
You can run both of these commands on the project within `sail` using the command `sail php ./vendor/bin/phpstan &&
sail php ./vendor/bin/pest`.

The database should be seeded using `sail artisan db:seed` before running the unit tests as PEST will need this for
testing against.

### Code Style and Guide

Code styling should follow Laravel code-style, which most IDEs have a setting. Pint is configured to run on builds and
you should run `sail php ./vendor/bin/pint` before pushing to fix and enforce code styling. You can also run the same
command with `sail php ./vendor/bin/pint --test` to see if all files adhere to code styling.

PEST is configured to
enforce [strict types](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict).

Each PHP file in the application, dependencies being the exception, should start with:

```php
<?php

declare(strict_types=1);
```

### Bitbucket Pipelines

Pipelines runs on pushes to `master`. The pushes are done through a pull request. You can see the pipeline status on
[bitbucket.org](https://bitbucket.org/mount7freiburg/termin.mount7.com/pipelines)

If the pipeline fails you can test locally:

```shell
docker build --memory=1g --memory-swap=1g -t chris/bbtermin:tag -f pipelinetest.dockerfile .
docker run --network=host --name bbmysql -e MYSQL_DATABASE='laravel' -e MYSQL_ROOT_PASSWORD='password' -e MYSQL_ROOT_HOST='%' -e MYSQL_USER='sail' -e MYSQL_PASSWORD='password' -d mysql:8.0
docker run -it --network=host --memory=4g --memory-swap=4g --memory-swappiness=0 --cpus=4 --entrypoint=/bin/bash chris/bbtermin:tag
```
