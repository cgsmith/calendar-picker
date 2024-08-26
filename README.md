# MVP
    
- Deploy to staging
- Allow translations and localizations to work
- Change frontend version to toggle light/dark mode
- Send email notification when appointment is created
- Send email notification when appointment is deleted AND in the future
- Add User profile picture
- Confirm appointment is sent to queue


# Appointment Booker

[German Documentation](./README_de.md)

The appointment booker is based on [Laravel](https://laravel.com/docs) and [Filament](https://filamentphp.com/docs).
Laravel is the PHP framework and Filament is an admin panel builder for rapid application development. You should take
a moment to familiarize your self with the [directory structure](https://laravel.com/docs/10.x/structure) of a Laravel
application.

You will need `php` and `composer` installed locally. See [Laravel's documentation](https://laravel.com/docs/10.x/sail#configuring-a-shell-alias) for configuring an alias 
for sail.

1. `php composer install` - Installs dependencies
2. `sail up -d` - Runs docker compose and other Laravel functions
3. `sail artisan migrate:fresh` - Installs db migrations can be run with `--seed` option
4. `sail artisan db:seed` - seeds database with test data
5. You can access the local system by navigating to http://laravel.test

> Note: Don't have a `.env` file? Copy it from [.env.example](./.env.example)!

### Staging server standup

*Assumes you are running a debian based VM. 

1. Spin up a VM or instance
2. `sudo apt update && sudo apt upgrade`
3. `sudo apt install unzip`
4. Open SSH, HTTP, and HTTPS. Limit the port access where required.

**PHP Install**

1. `sudo add-apt-repository ppa:ondrej/php`
2. `sudo apt update`
3. `sudo apt install php8.3 php8.3-cli php8.3-{bcmath,common,fpm,curl,mbstring,sqlite3,mysql,intl,xml,zip}`

**Caddy Install**

```
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https curl
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
sudo apt update
sudo apt install caddy
sudo usermod -a -G www-data $USER 
```

1. `sudo vim /etc/caddy/Caddyfile` paste the following in the file

```
staging.termin.mount7.com {
        root * /var/www/staging.termin.mount7.com/public
        encode zstd gzip

        php_fastcgi unix//run/php/php8.3-fpm.sock
        file_server
}
```

**MySQL 8 Config**

1. `sudo apt install mysql-server-8.0`
2. `sudo mysql`
3. `CREATE USER 'sail'@'localhost' IDENTIFIED BY 'password';`
4. `GRANT ALL PRIVILEGES ON * . * TO 'sail'@'localhost';`
5. `CREATE DATABASE laravel;`

### Local Setup Troubleshooting steps

##### laravel.test does not work

* Add `laravel.text` to your /etc/hosts file which points to 127.0.0.1
* If you changed the `APP_PORT` you will need to append that to your request

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

Unit tests are written with [PEST](https://pestphp.com/). Static analysis is done with [PHPStan](https://phpstan.org/). You can run both of these commands
on the project within `sail` using the command `sail composer test`.

The database should be seeded using `sail artisan db:seed` before running the unit tests as PEST will need this for
testing against.

### Code Style and Guide

Code styling should follow Laravel code-style, which most IDEs have a setting. Pint is configured to run on builds and
you should run `sail php ./vendor/bin/pint` before pushing to fix and enforce code styling. You can also run the same
command with `sail php ./vendor/bin/pint --test` to see if all files adhere to code styling.


> Note: Pint will run code styling with `--test` when running `composer test`

PEST is configured to
enforce [strict types](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict).

Each PHP file in the application, dependencies being the exception, should start with:

```php
<?php

declare(strict_types=1);
```

### Bitbucket Pipelines for Continuous Deployment

[Pipelines](https://support.atlassian.com/bitbucket-cloud/docs/use-pipes-in-bitbucket-pipelines/) runs on pushes
to `master`. The pushes are done through a pull request and executes the commmands in `./bitbucket-pipelines.yml`. You 
can see the pipeline status on [bitbucket.org](https://bitbucket.org/mount7freiburg/termin.mount7.com/pipelines). The 
Bitbucket Repository will require the following variables setup to work properly. These are located under 
_Repository Settings -> Repository Variables_ 

| Repository Variable | Setup in Bitbucket        | Description                                                                                                       |
|---------------------|---------------------------|-------------------------------------------------------------------------------------------------------------------|
| `USER`              | mount7                    | Bitbucket uses this user to execute SSH and SCP commands.                                                         |
| `BUILD_PATH`        | staging.termin.mount7.com | The relative path to drop the ZIP file in `/var/builds/$BUILD_PATH` which is defined in `bitbucket-pipelines.yml` |
| `SERVER`            | staging.termin.mount7.com | Server Bitbucket will use for sending ZIP file to and executing commands                                          |
| `SSH_PORT`          | 9963                      | Port used for SCP and SSH commands                                                                                |
| `SSH_DEBUG`         | false                     | Debug the SSH connection by setting to true                                                                       |

### Process for the Pipeline

1. The pipeline builds the project with npm and composer.
2. Composer tests are run in the docker container the pipeline has build
3. Successful builds create two artifacts. One build.zip and the deploy.sh file.
4. Bitbucket uploads the build.zip and deploy.sh to the `BUILD_PATH` on the remote `SERVER` specified.
5. Bitbucket then calls `deploy.sh` with the appropriate SHA and branch and your app is deployed! 🚀🚀🚀

### Deployer

Deployer is used for the atomic deployments. An atomic deployment simply changes the symlink for the webserver and then 
restarts the webserver after running any database migrations. This process, like all processes, can always be improved 
upon. An atomic deployment allows a server administrator to symlink to a prior version of working code as long as they 
navigate to the correct git SHA and change the symlink. In the future the deploy.sh script could probably perform 
a database backup before a migration is applied.

Deployer can be run from the command line with a command like below:

```shell
export SENTRY_API_AUTH_TOKEN=token-here && ./vendor/bin/dep deploy 
```

The token is provided to the deployer script so releases can be tracked on Sentry.io.

### Troubleshooting pipeline issues locally

If the pipeline fails you can test locally with Docker. Bitbucket has 
[decent documentation](https://confluence.atlassian.com/bbkb/troubleshooting-bitbucket-pipelines-1141505226.html)
for doing this. They also have a link that discusses "Troubleshooting locally with Docker". Below is an example of how
I was able to troubleshoot the pipeline issues.

```shell
docker build --memory=1g --memory-swap=1g -t chris/bbtermin:tag -f my.dockerfile .
docker run --network=host --name bbmysql -e MYSQL_DATABASE='laravel' -e MYSQL_ROOT_PASSWORD='password' -e MYSQL_ROOT_HOST='%' -e MYSQL_USER='sail' -e MYSQL_PASSWORD='password' -d mysql:8.0
docker run -it --network=host --memory=4g --memory-swap=4g --memory-swappiness=0 --cpus=4 --entrypoint=/bin/bash chris/bbtermin:tag
```

If you receive an error about the container being used for mysql - you can `docker rm container-name` and restart.

You can also ignore any error about the kernel not supporting memory swappiness
