<?php

/* DB TASKS
/* --------------------- */

// BACKUP DB REMOTE TO LOCAL

task('db:remote:backup', function() {
    $now = time();
    set('dump_path', '{{deploy_path}}/shared/db_backups/');
    set('dump_file', getenv('WP_THEME') . $now . '.sql');
    set('dump_filepath', get('dump_path') . get('dump_file'));

    writeln('<comment>> Remote dump : <info>' . get('dump_file') .' </info></comment>');
    run('mkdir -p ' . get('dump_path'));
    run('cd {{deploy_path}}/current/ && wp db export ' . get('dump_filepath') . ' --add-drop-table');

    runLocally('mkdir -p db_backups');
    download('db_backups/' . get('dump_file'), get('dump_filepath'));

})->desc('Download backup database');

// BACKUP DB LOCAL TO REMOTE

task('db:local:backup', function() {
    $now = time();
    set('dump_path', '{{deploy_path}}/shared/db_backups/');
    set('dump_file', getenv('WP_THEME') . $now . '.sql');
    set('dump_filepath', get('dump_path') . get('dump_file'));

    writeln('<comment>> Local dump : <info>' . get('dump_file') .' </info></comment>');
    runLocally('mkdir -p db_backups');
    runLocally('wp db export db_backups/' . get('dump_file') . ' --add-drop-table');

    run('mkdir -p ' . get('dump_path'));
    upload('db_backups/' . get('dump_file'),  get('dump_filepath'));

})->desc('Upload backup database');

// CREATE DB

task('db:create', function() {
    writeln('<comment>> Create database. </comment>');
    run('cd {{deploy_path}}/current/ && wp db create');

})->desc('Exports DB');

// PULL DB

task('db:cmd:pull', function() {
    writeln('<comment>> Imports remote db to local :<info>' . get('dump_file') . '</info> </comment>');
    runLocally('wp db import db_backups/' . get('dump_file'));
    runLocally('wp search-replace ' . get('remote_url') . ' ' . get('local_url'));
    runLocally('rm -f db_backups/' . get('dump_file'));

})->desc('Imports DB');

// PUSH DB

task('db:cmd:push', function() {
    writeln('<comment>> Exports local db to remote : <info>' . get('dump_file') . '</info>... </comment>');
    run('cd {{deploy_path}}/current && wp db import ' . get('dump_filepath'));
    run('cd {{deploy_path}}/current && wp search-replace ' . get('local_url') . ' ' . get('remote_url'));
    run('rm -f ' . get('dump_filepath') );

})->desc('Exports DB');

// DB GET URI

task('db:get:uri', function() {
    $tmp_dir = dirname(__DIR__) . '/../.tmp/';
    $local_env = '.local.env';
    $remote_env = '.remote.env';

    runLocally('mkdir -p ' . $tmp_dir);
    runLocally('cp .env ' . $tmp_dir . $local_env );
    download($tmp_dir . $remote_env, '{{deploy_path}}/shared/.env');

    $dotenvlocal = new Dotenv\Dotenv($tmp_dir, $local_env);
    if (file_exists($tmp_dir . $local_env)) {
        $dotenvlocal->overload();
        $dotenvlocal->required(['WP_HOME']);
        set('local_url', getenv('WP_HOME'));
    }

    $dotenvremote = new Dotenv\Dotenv($tmp_dir, $remote_env);
    if (file_exists($tmp_dir . $remote_env)) {
        $dotenvremote->overload();
        $dotenvremote->required(['WP_HOME']);
        set('remote_url', getenv('WP_HOME'));
    }

    runLocally('rm -rf .tmp');

})->desc('Download backup database');
