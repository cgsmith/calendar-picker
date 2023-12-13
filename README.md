## Appointment Booker

The appointment booker is based on [Laravel](https://laravel.com/docs) and [Filament](https://filamentphp.com/docs).
Laravel is the PHP framework and Filament is an admin panel builder for rapid application development. You should take
a moment to familiarize your self with the [directory structure](https://laravel.com/docs/10.x/structure) of a Laravel
application.

1. `php composer install` - Installs dependencies
2. `sail up -d` - Runs docker compose and other Laravel functions
3. `sail artisan migrate:fresh` - Installs db migrations
4. `sail artisan db:seed` - seeds database with test data

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
