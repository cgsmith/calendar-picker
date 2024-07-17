<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/sentry.php';


// Config
set('repository', 'git@bitbucket.org:mount7freiburg/termin.mount7.com.git');

// Tasks
task('asset-build', function () {
    if (get('labels')['env'] === 'production') {
        writeln('<info>Building assets locally for production environment</info>');
        runLocally('npm install');
        runLocally('npm run build');
        upload(__DIR__ . '/public/build', '{{release_path}}/public');
    } else {
        run('cd {{release_path}} && npm install');
        run('cd {{release_path}} && npm run build');
    }

});
// https://termin.mount7.com/JhmB2tGGrRimZLn7
// Upload remote path
task('upload', function () {
    upload(__DIR__ . "/", '{{release_path}}');
});


// Hosts
host('staging.termin.mount7.com')
    ->setLabels([
        'env' => 'staging'
    ])
    ->setIdentityFile('~/.ssh/id_rsa')
    ->setRemoteUser('ubuntu')
    ->setDeployPath('/var/www/staging.termin.mount7.com');
host('termin.mount7.com')
    ->setLabels([
        'env' => 'production'
    ])
    ->set('bin/php', function() {
        return '/usr/local/php8.3/bin/php';
    })
    ->set('remote_user', 'ahbukvxl')
    ->set('deploy_path', '/home/ahbukvxl/termin.mount7.com')
    ->set('http_user', 'ahbukvxl');

set('sentry', [
    'organization' => 'mount7-gmbh',
    'projects' => [
        'termin-mount7'
    ],
    'token' => env('SENTRY_API_AUTH_TOKEN')
]);

// Hooks
after('deploy:vendors', 'asset-build');
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:sentry');
