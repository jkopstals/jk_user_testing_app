<?php
namespace App;

/**
 * A config reader class, that exposes config options
 * Can be used with a different basePath for unittests or different environments
 */
class AppConfig
{
    private $config = [];

    public function __construct($basePath)
    {
        /**
         * usually an .env file would contain secrets - and is a better way to handle environment specific configuration.
         * But in this case config.php is being ignored in .gitignore,
         * thus not exposing potentially sensitive passwords/keys
         */

        $this->config = require $basePath.'\config.php';
    }

    /**
     * Config option getter
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return null;
    }

}


