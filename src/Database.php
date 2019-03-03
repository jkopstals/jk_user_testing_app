<?php
declare(strict_types=1);
namespace App;

use \PDO;
use \PDOException;

class Database
{
    protected $host;
    protected $dbname;
    protected $port;
    protected $username;
    protected $charset;
    
    protected $connection;

    public function __construct($host = 'localhost', $dbname = '',  $username = '', $password = '', $port = 3306, $charset = 'utf8mb4') {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->port = $port;
        $this->charset = $charset;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $connection_string = "mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=$this->charset";
        try {
            $this->connection = new PDO($connection_string, $this->username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * A way to call native connection object (PDO) methods
     */ 
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->connection, $method), $args);
    }


    /**
     * Run database query with or without additional parameters
     */
    public function query($sql, $data = [])
    {
        if (!$data)
        {
             return $this->connection->query($sql);
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return $stmt;
    }

    /**
     * Build database schema with primitive migration logic
     * Run in terminal during deployment or for testing
     */
    public function createSchema()
    {
        $migrations_done = [];
        $migrations = require( __DIR__ . '.\..\database\migrations.php');

        foreach ($migrations as $migration_id => $migration) {
            if (!isset($migration['up'])) {
                throw new \Exception($migration_id. ' missing up script');
            }
            
            $this->query($migration['up']);
            $migrations_done[] = $migration_id;
        }

        $all_migrations_done = empty(array_diff(array_keys($migrations), $migrations_done)); 
        return $all_migrations_done;
    }

    /**
     * Drop database schema
     */
    public function dropSchema()
    {
        $migrations_done = [];
        $migrations = require( __DIR__ . '.\..\database\migrations.php');

        $migrations_reverse = array_reverse($migrations);
        foreach ($migrations_reverse as $migration_id => $migration) {
            if (!isset($migration['down'])) {
                throw new \Exception($migration_id. ' missing down script');
            }

            $this->query($migration['down']);
            $migrations_done[] = $migration_id;
        }

        $all_migrations_done = empty(array_diff(array_keys($migrations), $migrations_done)); 
        return $all_migrations_done;
    }
}