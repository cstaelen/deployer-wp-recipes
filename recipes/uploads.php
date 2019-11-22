<?php

/* WP UPLOADS TASK
/* --------------------- */

namespace Deployer;

task('uploads:sync', function() {
    $server = \Deployer\Task\Context::get()->getHost();
    $upload_dir = 'web/app/uploads';
    $user       = $server->getUser();
    $host       = $server->getHostname();
    $port = $server->getPort() ? ' -p ' . $server->getPort() : '';
    $identityFile = $server->getIdentityFile() ? ' -i ' . $server->getIdentityFile() : '';

    writeln('<comment>> Receive remote uploads ... </comment>');
    runLocally("rsync -avzO --no-o --no-g -e 'ssh$port$identityFile' $user@$host:{{deploy_path}}/shared/$upload_dir/ $upload_dir");

    writeln('<comment>> Send local uploads ... </comment>');
    runLocally("rsync -avzO --no-o --no-g -e 'ssh$port$identityFile' $upload_dir/ $user@$host:{{deploy_path}}/shared/$upload_dir");

})->desc('Sync uploads');
