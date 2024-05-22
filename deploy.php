<?php
namespace Deployer;

require 'recipe/laravel.php';
// Config
set('repository', 'git@bitbucket.org:mount7freiburg/termin.mount7.com.git');

// Tasks
task('asset-build', function () {
    run('cd {{release_path}} && npm install');
    run('cd {{release_path}} && npm run build');
});

task('restart-service', function() {
    run('sudo service laravel-worker restart');
});

// Hosts
host('staging.termin.mount7.com')
    ->setLabels([
        'env' => 'staging'
    ])
    ->setIdentityFile('~/.ssh/id_rsa')
    ->setRemoteUser('ubuntu')
    ->setDeployPath('/var/www/staging.termin.mount7.com');
/*host('termin.mount7.com')
    ->set('remote_user', 'ubuntu')
    ->set('deploy_path', '~/Mount7 Termin');*/

// Hooks
after('deploy:vendors', 'asset-build');
after('deploy', 'restart-service');
after('deploy:failed', 'deploy:unlock');
