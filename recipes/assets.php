<?php

/* ASSETS TASK
/* --------------------- */

namespace Deployer;

task('deploy:assets', function() {

    $config = get('wp-recipes');

    if (!empty($config['gulp_cmd'])) {
        writeln('<comment>> Compile assets locally ...</comment>');
        writeln($config['gulp_cmd']);
        runLocally('cd ' . $config['theme_dir'] . $config['theme_name'] . ' && ' . $config['gulp_cmd']);
    }
    writeln('<comment>> Upload assets ...</comment>');
    run('rm  -rf ' . $config['theme_dir'] . $config['theme_name']);
    upload( $config['assets_dist'], '{{deploy_path}}/' . $config['assets_dist']);

})->desc('Upload dist assets folder');
