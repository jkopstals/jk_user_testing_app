<?php 

namespace App\Repositories;

use \App\Database;
use \PDO;
use \App\Models\Option;

class OptionRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function save(Option $option)
    {
        // If the ID is set, we're updating an existing record
        if (isset($option->id)) {
            return $this->update($option);
        }

        $stmt = $this->db->prepare('
            INSERT INTO options 
                (question_id, uuid, label, is_correct_answer, deleted_at, sequence) 
            VALUES 
                (:question_id, :uuid, :label, :is_correct_answer, :deleted_at, :sequence)
        ');
        $stmt->bindParam(':question_id', $option->question_id);
        $stmt->bindParam(':uuid', $option->uuid);
        $stmt->bindParam(':label', $option->label);
        $stmt->bindParam(':is_correct_answer', $option->is_correct_answer);
        $stmt->bindParam(':deleted_at', $option->deleted_at);
        $stmt->bindParam(':sequence', $option->sequence);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        return $this->find($id);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('
            SELECT options.* 
             FROM options 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'Option'
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Option');
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->prepare('
            SELECT * FROM options
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Option');
        return $stmt->fetchAll();
    }

    
    public function findByQuestionID($question_id)
    {
        $stmt = $this->db->prepare('
            SELECT options.* 
            FROM options
            WHERE question_id = :question_id
            ORDER BY RAND(), options.sequence ASC, options.id ASC
        ');
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\Option');
        return $stmt->fetchAll();
    }

    public function update(Option $option)
    {
        if (!isset($option->id)) {
            throw new \LogicException(
                'Cannot update option that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE options
            SET question_id = :question_id,
                uuid = :uuid,
                label = :label,
                is_correct_answer = :is_correct_answer,
                deleted_at = :deleted_at,
                sequence = :sequence
            WHERE id = :id
        ');
        $stmt->bindParam(':question_id', $option->question_id);
        $stmt->bindParam(':uuid', $option->uuid);
        $stmt->bindParam(':label', $option->label);
        $stmt->bindParam(':is_correct_answer', $option->is_correct_answer);
        $stmt->bindParam(':deleted_at', $option->deleted_at);
        $stmt->bindParam(':sequence', $option->sequence);
        $stmt->bindParam(':id', $option->id);
        $stmt->execute();
        return $this->find($option->id);
    }
}