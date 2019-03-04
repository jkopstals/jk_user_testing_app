<?php 

namespace App\Repositories;

use \App\Database;
use \PDO;
use \App\Models\Answer;

class AnswerRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Answer $answer)
    {
        // If the ID is set, we're updating an existing record
        if (isset($answer->id)) {
            return $this->update($answer);
        }

        $stmt = $this->db->prepare('
            INSERT INTO answers 
                (respondent_id, question_id, option_id, deleted_at) 
            VALUES 
                (:respondent_id, :question_id, :option_id, :deleted_at)
        ');
        $stmt->bindParam(':respondent_id', $answer->respondent_id);
        $stmt->bindParam(':question_id', $answer->question_id);
        $stmt->bindParam(':option_id', $answer->option_id);
        $stmt->bindParam(':deleted_at', $answer->deleted_at);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        return $this->find($id);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('
            SELECT answers.* 
             FROM answers 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Answer'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Answer');
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare('
            SELECT * FROM answers
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Answer');
        return $stmt->fetchAll();
    }

    public function saveNewAnswerByUUIDs($respondent_uuid, $option_uuid)
    {
        $stmt = $this->db->prepare('
            INSERT IGNORE INTO answers 
            (respondent_id, question_id, option_id)
            SELECT r.id, o.question_id, o.id
            FROM respondents r 
                LEFT JOIN questions q on r.test_id = q.test_id
                LEFT JOIN options o on q.id = o.question_id
            WHERE r.uuid = :respondent_uuid AND o.uuid = :option_uuid
        ');
        $stmt->bindParam(':respondent_uuid', $respondent_uuid);
        $stmt->bindParam(':option_uuid', $option_uuid);
        return $stmt->execute();
    }

    public function update(Answer $answer)
    {
        if (!isset($answer->id)) {
            throw new \LogicException(
                'Cannot update answer that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE answers
            SET respondent_id = :respondent_id,
                question_id = :question_id,
                option_id = :option_id,
                deleted_at = :deleted_at
            WHERE id = :id
        ');
        $stmt->bindParam(':respondent_id', $answer->respondent_id);
        $stmt->bindParam(':question_id', $answer->question_id);
        $stmt->bindParam(':option_id', $answer->option_id);
        $stmt->bindParam(':deleted_at', $answer->deleted_at);
        $stmt->bindParam(':id', $answer->id);
        $stmt->execute();
        return $this->find($answer->id);
    }
}