<?php 

namespace App\Repositories;

use \App\Database;
use \PDO;
use \App\Models\Respondent;

class RespondentRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Respondent $respondent)
    {
        // If the ID is set, we're updating an existing record
        if (isset($respondent->id)) {
            return $this->update($respondent);
        }

        $stmt = $this->db->prepare('
            INSERT INTO respondents 
                (uuid, name, test_id, completed_at, deleted_at) 
            VALUES 
                (:uuid, :name, :test_id, :completed_at, :deleted_at)
        ');
        $stmt->bindParam(':uuid', $respondent->uuid);
        $stmt->bindParam(':name', $respondent->name);
        $stmt->bindParam(':test_id', $respondent->test_id);
        $stmt->bindParam(':completed_at', $respondent->completed_at);
        $stmt->bindParam(':deleted_at', $respondent->deleted_at);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        return $this->find($id);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('
            SELECT respondents.* 
             FROM respondents 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Respondent'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Respondent');
        return $stmt->fetch();
    }

    
    public function findByUUID($id)
    {
        $stmt = $this->db->prepare('
            SELECT respondents.* 
             FROM respondents 
             WHERE uuid = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Respondent'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Respondent');
        return $stmt->fetch();
    }

    public function getTestProgressByUUID($id)
    {
        $stmt = $this->db->prepare('
            SELECT r.id, count(q.id) question_count, count(a.id) as answer_count
            FROM respondents r LEFT JOIN questions q ON r.test_id = q.test_id
            LEFT JOIN answers a ON q.id = a.question_id and a.respondent_id = r.id
            WHERE r.uuid = :id
            GROUP BY r.id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        //returns array
        return $stmt->fetch();
    }

    public function getTestResultByUUID($id)
    {
        $stmt = $this->db->prepare('
            SELECT r.id, count(distinct q.id) question_count, count(distinct a.id) as answer_count, count(distinct IF(o.is_correct_answer=1,a.id,null)) as correct_count
            FROM respondents r LEFT JOIN questions q ON r.test_id = q.test_id
            LEFT JOIN answers a ON q.id = a.question_id and a.respondent_id = r.id
            LEFT JOIN options o ON o.id = a.option_id
            WHERE r.uuid = :id
            GROUP BY r.id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        //returns array
        return $stmt->fetch();
    }

    
    public function setCompletedByUUID($id)
    {
        $stmt = $this->db->prepare('
            UPDATE respondents r
            SET r.completed_at = NOW()
            WHERE r.uuid = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare('
            SELECT * FROM respondents
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Respondent');
        return $stmt->fetchAll();
    }

    public function update(Respondent $respondent)
    {
        if (!isset($respondent->id)) {
            throw new \LogicException(
                'Cannot update respondent that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE respondents
            SET uuid = :uuid,
                name = :name,
                test_id = :test_id,
                completed_at = :completed_at,
                deleted_at = :deleted_at
            WHERE id = :id
        ');
        $stmt->bindParam(':uuid', $respondent->uuid);
        $stmt->bindParam(':name', $respondent->name);
        $stmt->bindParam(':test_id', $respondent->test_id);
        $stmt->bindParam(':completed_at', $respondent->completed_at);
        $stmt->bindParam(':deleted_at', $respondent->deleted_at);
        $stmt->bindParam(':id', $respondent->id);
        $stmt->execute();
        return $this->find($respondent->id);
    }
}