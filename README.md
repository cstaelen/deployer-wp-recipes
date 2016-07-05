# Deployer WP Recipes

Those recipes was written using Bedrock inspire wordpress stack, using dotenv package.
But it can be use in other env configuration .

##Features
- Deploy repository code & gulp compiled files
- Sync Wordpress uploads
- Pull and push Wordpress database
- Clean up files

##Requirements

Deployer PHP
WP CLI
Dot env (optional)

##Installation

Make sure to include recipe files in your `deploy.php`:

    require 'vendor/cstaelen/deployer-wp-recipes/recipes/assets.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/cleanup.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/db.php';
    require 'vendor/cstaelen/deployer-wp-recipes/recipes/uploads.php';


##Configuration

Just add those lines below in your `deploy.php` with your own values :

    set('wp-recipes', [
	    'theme_name'        => 'Your WP theme folder name',
	    'theme_dir'         => 'path/to/your/theme/folder',
	    'wwwroot_dir'       => 'web', //VHOST ROOT DIR
	    'shared_dir'        => '{{deploy_path}}/shared', 
	    'gulp_cmd'          => 'gulp build',
	    'bower_cmd'         => 'bower install',
	    'npm_cmd'           => 'npm install',
	    'assets_dist'       => 'path/to/theme/folder/dist',
	    'local_wp_url'      => 'http://local.dev',
	    'remote_wp_url'     => 'http://mywebsite.com',
	    'clean_after_deploy'=>  [
	        'deploy.php',
	        '.gitignore',
	        '*.md'
	    ]
    ]);

##Available tasks

Upload your WP database : `dep db:push prod`
Download your WP database : `dep db:pull prod`
Sync WP uploads with rsync : `dep uploads:sync prod`

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
