<?php
declare(strict_types=1);
namespace App;

/**
 * Application core object
 */
class App
{
    protected static $instance;

    private $path;
    private $config;
    private $db;
    private $router;

    public function __construct($path, AppConfig $config)
    {
        static::setInstance($this); //there will only be one instance of app needed

        //app needs to know the base path of the application, to be able to pass/construct absolute paths
        $this->path = $path;
        $this->config = $config;
        $this->connectToDb();

        

        $this->router = new Router();
        //initialize Router (routes -> Controllers -> return Views or Resources)
    }

    public function run()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        $request_uri = $_SERVER['REQUEST_URI'];

        return Router::call($request_method, $request_uri);
    }

    public function connectToDb() 
    {
        if (!$this->db) {
            $db_host = $this->config->get('db_host');
            $db_name = $this->config->get('db_name');
            $db_user = $this->config->get('db_user');
            $db_password = $this->config->get('db_password');
            $db_port = $this->config->get('db_port');
            $db_charset = $this->config->get('db_charset');
            
            $this->db = new Database($db_host, $db_name, $db_user, $db_password, $db_port, $db_charset);
        }
    }

    public function db()
    {
        return $this->db;
    }

    public function router()
    {
        return $this->router;
    }

    public static function app()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public static function setInstance($app)
    {
        return static::$instance = $app;
    }
}