<?php

/* CLEANUP TASK
/* --------------------- */

task('deploy:cleanup', function() {

    $themedir = 'web/app/themes/' . getenv('WP_THEME');

    writeln('<comment>> Cleanin\'up that mess ... !</comment>');

    $targets = [
        'deploy.php',
        'deploy.php',
        'config/recipes',
        'config/servers.yml',
        'config/servers.yml',
        '.gitignore',
        '.env.example',
        '.travis.yml',
        '*.md',
        '*.json',
        '*.sh',
        '*.xml',
        $themedir . '/assets',
        $themedir . '/gulpfile.js',
        $themedir . '/.gitignore',
        $themedir . '/.editorconfig',
        $themedir . '/.bowerrc',
        $themedir . '/.jscsrc',
        $themedir . '/.jshintrc',
        $themedir . '/*.md',
        $themedir . '/*.json',
        $themedir . '/.travis.yml',
        $themedir . '/*.xml',
    ];

    foreach ($targets as $element) {
        run('rm -rf {{release_path}}/' . $element);
    }

})->desc('Upload dist assets folder');
?>
