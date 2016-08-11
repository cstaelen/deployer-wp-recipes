<?php

/* ASSETS TASK
/* --------------------- */

task('deploy:assets', function() {

    $config = get('wp-recipes');

    writeln('<comment>> Compile assets locally ...</comment>');
    writeln($config['gulp_cmd']);
    runLocally('cd ' . $config['theme_dir'] . $config['theme_name'] . ' && ' . $config['gulp_cmd']);
    writeln('<comment>> Uploads assets ...</comment>');
    run('rm  -rf ' . $config['theme_dir'] . $config['theme_name']);
    upload( $config['theme_dir'] . $config['theme_dist'], '{{release_path}}/' . $config['theme_dir'] . $config['theme_name']);

})->desc('Upload dist assets folder');
