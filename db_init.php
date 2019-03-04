<?php
/**
 * this file will contains bootstrapping for console
 */

//as the projects aim is not to use any external libraries, 
//composer is used only for development tools and for class autoloading purposes
require __DIR__ . '/vendor/autoload.php';

$config = new App\AppConfig(__DIR__);
$app = new App\App(__DIR__, $config);

$db = $app->db();
$db->createSchema();
$db->seed();
print('DB INIT DONE');
