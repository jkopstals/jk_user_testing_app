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
    private $response;

    public function __construct($path, AppConfig $config)
    {
        /**
         * since there will only be one instance of app - the instance is set to a class 
         * constant variable, that can be retrieved from anywhere
         */
        static::setInstance($this); //

        /**
         * base path of the application to help pass/construct absolute paths 
         */
        $this->path = $path;

        /**
         * config that can be exchanged for different environments and while testing
         */
        $this->config = $config;

        /**
         * Establishing a connection to DB
         */
        $this->db = $this->getDbConnection(
            $config->get('db_host'),
            $config->get('db_name'),
            $config->get('db_user'),
            $config->get('db_password'),
            $config->get('db_port'),
            $config->get('db_charset')
        );

        /**
         * Request class that combines all data associated with the request in one object
         */
        $this->request = new Request();

        /**
         * Response class handles setting headers and sending response
         */
        $this->response = new Response();

        /**
         * Simplistic templating class, can render php files with scoped variables
         */
        $this->view = new View($this->path.'\\templates\\');

        /**
         * Router allows registering http methods and uri's to dynamically callable constructor class methods 
         */
        $this->router = new Router();
    }

    /**
     * Setting up routes
     */
    public function init()
    {
        $this->router->map('GET','/', '\App\Controllers\TestController::start');
        
        $this->router->map('POST','/', '\App\Controllers\TestController::show');

        $this->router->map('POST', '/api/answers/', '\App\Controllers\ApiAnswerController::store');
    }

    public function run()
    {
        $this->router->dispatch($this->request);
    }

    public function getDbConnection($db_host, $db_name, $db_user, $db_password, $db_port, $db_charset) 
    {
        return new Database($db_host, $db_name, $db_user, $db_password, $db_port, $db_charset);
    }

    public function db()
    {
        return $this->db;
    }

    public function view()
    {
        return $this->view;
    }

    public function router()
    {
        return $this->router;
    }

    public function request()
    {
        return $this->request;
    }

    public function response() 
    { 
        return $this->response;
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

    public function getBasePath()
    {
        return $this->path;
    }
}