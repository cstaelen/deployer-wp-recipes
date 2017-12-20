<?php

/* ASSETS TASK
/* --------------------- */

namespace Deployer;

task('deploy:assets', function() {

    $config = get('wp-recipes');

    writeln('<comment>> Run composer install in local directory ...</comment>');
    runLocally('cd ' . $config['theme_dir'] . $config['theme_name'] . ' && ' . $config['gulp_cmd']);

    writeln('<comment>> Upload assets ...</comment>');
    run('rm  -rf ' . $config['theme_dir'] . $config['theme_name']);
    upload( $config['assets_dist'], '{{release_path}}/' . $config['assets_dist']);

})->desc('Upload dist assets folder');
