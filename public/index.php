<?php
/**
 * Logic of the web application
 */
$parent_dir = dirname(__DIR__);

//as the projects aim is not to use any external libraries, 
//composer is used only for development tools and for class autoloading purposes
require $parent_dir . '/vendor/autoload.php'; 

$config = new App\AppConfig($parent_dir);

$app = new App\App($parent_dir, $config);

