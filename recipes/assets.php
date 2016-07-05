<?php

/*ASSETS TASK
/* --------------------- */

task('deploy:assets', function() {

    $themedir = 'web/app/themes/' . getenv('WP_THEME');
    $gulp_cmd = 'gulp build';

    writeln('<comment>> Compile assets locallly ...</comment>');
    writeln($gulp_cmd );
    runLocally('cd ' . $themedir . ' && ' . $gulp_cmd);

    writeln('<comment>> Uploads assets ...</comment>');
    upload($themedir . '/dist', '{{release_path}}/' . $themedir . '/dist');

})->desc('Upload dist assets folder');
