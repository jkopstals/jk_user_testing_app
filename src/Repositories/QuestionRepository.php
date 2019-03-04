<?php 

namespace App\Repositories;

use \App\Database;
use \PDO;
use \App\Models\Question;

class QuestionRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Question $question)
    {
        // If the ID is set, we're updating an existing record
        if (isset($question->id)) {
            return $this->update($question);
        }

        $stmt = $this->db->prepare('
            INSERT INTO questions 
                (uuid, description, test_id, deleted_at, sequence) 
            VALUES 
                (:uuid, :description, :test_id, :deleted_at, :sequence)
        ');
        $stmt->bindParam(':uuid', $question->uuid);
        $stmt->bindParam(':description', $question->description);
        $stmt->bindParam(':test_id', $question->test_id);
        $stmt->bindParam(':deleted_at', $question->deleted_at);
        $stmt->bindParam(':sequence', $question->sequence);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        return $this->find($id);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('
            SELECT questions.* 
             FROM questions 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Question'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Question');
        return $stmt->fetch();
    }

    public function findByTestUUID($uuid)
    {
        $stmt = $this->db->prepare('
            SELECT q.* 
            FROM questions q LEFT JOIN tests t ON q.test_id = t.id
            WHERE t.uuid = :uuid
        ');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Question');
        return $stmt->fetchAll();
    }

    public function findFirstUnansweredByRespondentUUID($uuid)
    {
        $stmt = $this->db->prepare('
            SELECT q.* 
            FROM questions q
                LEFT JOIN respondents r on q.test_id = r.test_id
                LEFT JOIN answers a on q.id = a.question_id and r.id = a.respondent_id
            WHERE r.uuid = :uuid AND a.id IS NULL
            ORDER BY q.sequence ASC, q.id ASC
        ');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Question');
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare('
            SELECT * FROM questions
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Question');
        return $stmt->fetchAll();
    }

    public function update(Question $question)
    {
        if (!isset($question->id)) {
            throw new \LogicException(
                'Cannot update question that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE questions
            SET uuid = :uuid,
                description = :description,
                test_id = :test_id,
                deleted_at = :deleted_at,
                sequence = :sequence
            WHERE id = :id
        ');
        $stmt->bindParam(':uuid', $question->uuid);
        $stmt->bindParam(':description', $question->description);
        $stmt->bindParam(':test_id', $question->test_id);
        $stmt->bindParam(':deleted_at', $question->deleted_at);
        $stmt->bindParam(':sequence', $question->sequence);
        $stmt->bindParam(':id', $question->id);
        $stmt->execute();
        return $this->find($question->id);
    }
}