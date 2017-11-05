# Deployer WP Recipes

For the record, those recipes was written using a personal Wordpress stack based on [Bedrock](https://roots.io/bedrock/) (Thanks to the great [roots.io](https://roots.io/) team), using [phpdotenv](https://github.com/vlucas/phpdotenv) package. But it can be used in many other configuration .

## Features
- Deploy repository code with gulp or webpack compiled files
- Sync and push Wordpress uploads
- Pull and push Wordpress database
- Clean up some files

## Requirements
- [Deployer PHP](http://deployer.org/)
- [WP CLI](https://wp-cli.org/)
- [phpdotenv](https://github.com/vlucas/phpdotenv) (optional)

## Installation

If you're using phpdotenv, run `composer require vlucas/phpdotenv`.

Make sure to include recipe files in your `deploy.php`:

    require 'vendor/cstaelen/deployer-wp-recipes/recipes/assets.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/cleanup.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/db.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/uploads.php';
    # Include if you're using phpdotenv
    # require 'vendor/vlucas/phpdotenv/src/Dotenv.php';

## Configuration

Just add those lines below in your `deploy.php` with your own values :

    set('wp-recipes', [
	    'theme_name'        => 'Your WP theme folder name', # e.g. 'mytheme'
	    'theme_dir'         => 'path/to/your/theme/folder', # if you're using Bedrock, '/web/app/themes/
	    'wwwroot_dir'       => 'web', //VHOST ROOT DIR
	    'shared_dir'        => '{{deploy_path}}/shared', 
	    'gulp_cmd'          => 'gulp build', # for webpack, use 'yarn && yarn run build:production'
	    'assets_dist'       => 'path/to/theme/folder/dist', # use '{{theme_dir}}/dist'
	    'local_wp_url'      => 'http://local.dev', 
	    'remote_wp_url'     => 'http://mywebsite.com',
	    'clean_after_deploy'=>  [
	        'deploy.php',
	        '.gitignore',
	        '*.md'
	    ]
    ]);

## Available tasks

Upload your WP database : `dep db:push prod`
Download your WP database : `dep db:pull prod`
Sync WP uploads with rsync : `dep uploads:sync prod`
Upload your local copy of WP uploads with rsync : `dep uploads:push prod`

You can also use those rules below in your `deploy.php` file to compile and deploy assets and cleanup some useless files on your staging/production server :

    after('deploy', 'deploy:assets');
    after('deploy', 'deploy:cleanup');
    

## WP recipes using phpdotenv

If you are using **phpdotenv** to configure your servers as the awesome Bedrock Wordpress Stack do, you can use those task rules below to grab `WP_HOME` value filled in your .env file.

    before('db:cmd:pull', 'env:uri');
    before('db:cmd:push', 'env:uri');

*In order to do that, we assume your local .env file is in the root project folder, and the remote one in the repo shared folder.*

Make sure to leave empty those config values :

    set('wp-recipes', [
   		...
   	    'local_wp_url'      => '',
   	    'remote_wp_url'     => ''
   	    ...
	]);
