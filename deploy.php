<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'tune_up');

// Project repository
set('repository', 'git@github.com:mariocardoza/tune_up.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('52.252.0.82')
	->stage('master')
	->user('tuneup')
	->set('branch', 'master')
    ->set('deploy_path', '/var/www/html/');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('artisan:optimize', function () {});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'artisan:migrate');

