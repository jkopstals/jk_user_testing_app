<?php
namespace App\Models;

class Option extends Model
{
    public $id;
    public $uuid;
    public $label;
    public $created_at;
    public $is_correct_answer;
    public $deleted_at;
    public $sequence;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->id = $data['id'];
            }

            $this->question_id = $data['question_id'];
            
            if (isset($data['uuid'])) { 
                $this->uuid = $data['uuid'];
            } else {
                $this->uuid = uniqid();
            }
            
            $this->label = $data['label'];

            if (isset($data['created_at'])) {
                $this->created_at = $data['created_at'];
            }
            
            if (isset($data['is_correct_answer'])) {
                $this->is_correct_answer = $data['is_correct_answer'];
            }
            
            if (isset($data['deleted_at'])) {
                $this->deleted_at = $data['deleted_at'];
            }
            
            if (isset($data['sequence'])) {
                $this->sequence = $data['sequence'];
            } else {
                $this->sequence = 0;
            }
        }
    }
}