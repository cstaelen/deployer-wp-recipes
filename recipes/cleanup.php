<?php

/* CLEANUP TASK
/* --------------------- */

task('deploy:cleanup', function() {

    $config = get('wp-recipes');

    writeln('<comment>> Cleanin\'up that mess ... !</comment>');

    $targets = $config['clean_after_deploy'];

    foreach ($targets as $element) {
        run('rm -rf {{release_path}}/' . $element);
    }

})->desc('Upload dist assets folder');
?>
