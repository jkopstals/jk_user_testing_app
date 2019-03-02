<?php
declare(strict_types=1);
namespace App;

/**
 * Application core object
 */
class App
{
    private $db;

    public function __construct($config)
    {
        $db_host = $config['db_host'];
        $db_name = $config['db_name'];
        $db_user = $config['db_user'];
        $db_password = $config['db_password'];
        $db_port = $config['db_port'];
        $db_charset = $config['db_charset'];
        
        $this->db = new Database($db_host, $db_name, $db_user, $db_password, $db_port, $db_charset);
        //initialize Router (routes -> Controllers -> return Views or Resources)
    }

    public function db() 
    {
        return $this->db;
    }
}