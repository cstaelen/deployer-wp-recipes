<?php

/* DB TASKS
/* --------------------- */

namespace Deployer;

// BACKUP DB REMOTE TO LOCAL

task('db:remote:backup', function() {

    $config = get('wp-recipes');

    $now = time();
    set('dump_path', $config['shared_dir']);
    set('dump_file', $config['theme_name']  . $now . '.sql');
    set('dump_filepath', get('dump_path') . get('dump_file'));

    writeln('<comment>> Remote dump : <info>' . get('dump_file') .' </info></comment>');
    run('mkdir -p ' . get('dump_path'));
    run('cd {{deploy_path}}/current/ && wp db export ' . get('dump_filepath') . ' --add-drop-table');

    runLocally('mkdir -p db_backups');
    download('db_backups/' . get('dump_file'), get('dump_filepath'));

})->desc('Download backup database');

// BACKUP DB LOCAL TO REMOTE

task('db:local:backup', function() {

    $config = get('wp-recipes');

    $now = time();
    set('dump_path', $config['shared_dir']);
    set('dump_file', $config['theme_name'] . $now . '.sql');
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

// GET ENV WP SITE URL

task('env:uri', function() {

    $config = get('wp-recipes');

    if ( ($config['local_wp_url'] === '') || ($config['remote_wp_url'] === '') ) {
        writeln('working with env files');
        $tmp_dir = dirname(__DIR__) . '/../.tmp/';
        $local_env = '.local.env';
        $remote_env = '.remote.env';

        runLocally('mkdir -p ' . $tmp_dir);
        runLocally('cp .env ' . $tmp_dir . $local_env );
        download($tmp_dir . $remote_env, $config['shared_dir'] . '/.env');


        $dotenvremote = new Dotenv\Dotenv($tmp_dir, $remote_env);
        if (file_exists($tmp_dir . $remote_env)) {
            $dotenvremote->overload();
            $dotenvremote->required(['WP_HOME']);
            set('remote_url', getenv('WP_HOME'));
        }

        $dotenvlocal = new Dotenv\Dotenv($tmp_dir, $local_env);
        if (file_exists($tmp_dir . $local_env)) {
            $dotenvlocal->overload();
            $dotenvlocal->required(['WP_HOME']);
            set('local_url', getenv('WP_HOME'));
        }

        runLocally('rm -rf .tmp');
    } else {
        writeln('working with config');
        set('local_url', $config['local_wp_url']);
        set('remote_url', $config['remote_wp_url']);
    }

})->desc('Download backup database');

/* --------------------- */
/*       DB TASKS         */
/* --------------------- */

task('db:push', [
    'db:local:backup',
    'db:cmd:push'
]);

task('db:pull', [
    'db:remote:backup',
    'db:cmd:pull'
]);
