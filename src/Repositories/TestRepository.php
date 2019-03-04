<?php 

namespace App\Repositories;

use \App\Database;
use \PDO;
use \App\Models\Test;

class TestRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Test $test)
    {
        // If the ID is set, we're updating an existing record
        if (isset($test->id)) {
            return $this->update($test);
        }

        $stmt = $this->db->prepare('
            INSERT INTO tests 
                (uuid, name, published_at, deleted_at, sequence) 
            VALUES 
                (:uuid, :name, :published_at, :deleted_at, :sequence)
        ');
        $stmt->bindParam(':uuid', $test->uuid);
        $stmt->bindParam(':name', $test->name);
        $stmt->bindParam(':published_at', $test->published_at);
        $stmt->bindParam(':deleted_at', $test->deleted_at);
        $stmt->bindParam(':sequence', $test->sequence);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        return $this->find($id);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('
            SELECT tests.* 
             FROM tests 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Test'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Test');
        return $stmt->fetch();
    }

    public function findByUUID($id)
    {
        $stmt = $this->db->prepare('
            SELECT tests.* 
             FROM tests 
             WHERE uuid = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Test'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Test');
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare('
            SELECT * FROM tests
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Test');
        return $stmt->fetchAll();
    }

    public function update(Test $test)
    {
        if (!isset($test->id)) {
            throw new \LogicException(
                'Cannot update test that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE tests
            SET uuid = :uuid,
                name = :name,
                published_at = :published_at,
                deleted_at = :deleted_at,
                sequence = :sequence
            WHERE id = :id
        ');
        $stmt->bindParam(':uuid', $test->uuid);
        $stmt->bindParam(':name', $test->name);
        $stmt->bindParam(':published_at', $test->published_at);
        $stmt->bindParam(':deleted_at', $test->deleted_at);
        $stmt->bindParam(':sequence', $test->sequence);
        $stmt->bindParam(':id', $test->id);
        $stmt->execute();
        return $this->find($test->id);
    }
}