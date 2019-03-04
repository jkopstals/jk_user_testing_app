<?php
/**
 * Logic of the web application
 */
$parent_dir = dirname(__DIR__);

//as the projects aim is not to use any external libraries, 
//composer is used only for development tools and for class autoloading purposes
require $parent_dir . '/vendor/autoload.php'; 

try {
    $config = new App\AppConfig($parent_dir);

    $app = new App\App($parent_dir, $config);

    $app->init();

    $app->run();
} catch (\Exception $e) {
    http_response_code(500);
    echo '<html><head><title>Error</title></head><body><h1>'.$e->getMessage().'</h1></body></html>';
}

