<?php

/* WP UPLOADS TASK
/* --------------------- */

task('uploads:sync', function() {
    $server = \Deployer\Task\Context::get()->getServer()->getConfiguration();
    $upload_dir = 'web/app/uploads';
    $user       = $server->getUser();
    $host       = $server->getHost();
    $port = $server->getPort() ? ' -p' . $server->getPort() : '';
    $identityFile = $server->getPrivateKey() ? ' -i ' . $server->getPrivateKey() : '';

    writeln('<comment>> Receive remote uploads ... </comment>');
    runLocally("rsync -avzO -e 'ssh$port$identityFile' $user@$host:{{deploy_path}}/shared/$upload_dir $upload_dir");

    writeln('<comment>> Send local uploads ... </comment>');
    runLocally("rsync -avzO -e 'ssh$port$identityFile' $upload_dir $user@$host:{{deploy_path}}/shared/$upload_dir");

})->desc('Sync uploads');
