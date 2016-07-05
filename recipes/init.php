<?php

/* INIT TASK
/* --------------------- */

task('init:shared', function() {

    writeln('<comment>> Initialize files ...</comment>');

    run('mkdir -p {{deploy_path}}/shared/web');
    run('cp {{release_path}}/web/.htaccess {{deploy_path}}/shared/web/.htaccess');

})->desc('Initialize htaccess');

task('init:theme', function() {
    writeln('<comment>> Initialize wp theme ...</comment>');
    runLocally('cd web/app/themes/' . getenv('WP_THEME') . ' && npm install');
    runLocally('cd web/app/themes/' . getenv('WP_THEME') . ' && bower install');
    runLocally('cd web/app/themes/' . getenv('WP_THEME') . ' && gulp');
    runLocally('wp theme activate ' . getenv('WP_THEME'));

})->desc('init wp theme');
