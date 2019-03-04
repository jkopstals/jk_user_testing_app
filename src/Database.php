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
     * Run in terminal during deployment or to set up testing databases
     */
    public function createSchema()
    {
        $migrations_done = [];
        $migrations = require( dirname(__DIR__) . '/database/migrations.php');

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
     * Drop database schema - for testing, or for temporary environments
     */
    public function dropSchema()
    {
        $migrations_done = [];
        $migrations = require( dirname(__DIR__) . '/database/migrations.php');

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

    /**
     * a way to clean seeded or testing data
     */
    public function cleanSchema()
    {
        $tables = ['answers', 'respondents', 'tests', 'questions', 'options'];
        foreach ($tables as $table) {
            $this->query("TRUNCATE TABLE $table;");
        }
    }

    /**
     * Simple seed function, that reads an array of data from a seeding file
     */
    public function seed()
    {
        $seed = require( dirname(__DIR__) . '/database/seeding.php');

        foreach ($seed as $table => $records) {
            $this->seedRecords($table, $records);
        }
    }

    /**
     * helper function, that is being called recursively to seed related objects
     */
    private function seedRecords($table, $records, $parent_id = null) {
        foreach ($records as $row) {
            if ($table == 'tests') {
                $testRepo = new \App\Repositories\TestRepository($this);
                $test = new \App\Models\Test($row);
                $test = $testRepo->save($test);
                if (isset($row['questions'])) {
                    $this->seedRecords('questions', $row['questions'], $test->id);
                }
            }
            if ($table == 'questions') {
                $questionRepo = new \App\Repositories\QuestionRepository($this);
                $question = new \App\Models\Question(array_merge($row,['test_id' => $parent_id]));
                $question = $questionRepo->save($question);
                if (isset($row['options'])) {
                    $this->seedRecords('options', $row['options'], $question->id);
                }
            }
            if ($table == 'options') {
                $optionRepo = new \App\Repositories\OptionRepository($this);
                $option = new \App\Models\Option(array_merge($row,['question_id' => $parent_id]));
                $optionRepo->save($option);
            }
        }
    }
}
