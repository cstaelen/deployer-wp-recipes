<?php

/* ASSETS TASK
/* --------------------- */

task('deploy:assets', function() {

    $config = get('wp-recipes');

    writeln('<comment>> Compile assets locally ...</comment>');
    writeln($config['gulp_cmd']);
    runLocally('cd ' . $config['theme_dir'] . ' && ' . $config['gulp_cmd']);
    //echo $config['assets_dist'];
    echo env('release_path') . '/' . $config['assets_dist'];
    writeln('<comment>> Uploads assets ...</comment>');
    upload( $config['assets_dist'], '{{release_path}}/' . $config['assets_dist']);

})->desc('Upload dist assets folder');
