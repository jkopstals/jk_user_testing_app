<?php
/**
 * this file will contain bootstrapping of the application for both website and testing
 */

require __DIR__ . '/helpers.php';
require __DIR__ . '/vendor/autoload.php'; 
//as the projects aim is not to use any external libraries, composer is used only for development tools and for class autoloading purposes


/**
 * usually an .env file would contain secrets - and is a better way to handle environment specific configuration.
 * But in this case config.php is being ignored in .gitignore,
 * thus not exposing potentially sensitive passwords/keys
 */
$config = require __DIR__ . '.\config.php';
$app = new App\App($config);

//quick debug console if script started from command line
if (php_sapi_name() == "cli") {
    \Psy\Shell::debug(get_defined_vars());
}
