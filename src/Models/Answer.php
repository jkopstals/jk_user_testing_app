<?php
namespace App\Models;

class Answer extends Model
{
    public $id;
    public $respondent_id;
    public $question_id;
    public $option_id;
    public $created_at;
    public $deleted_at;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->id = $data['id'];
            }
            
            $this->respondent_id = $data['respondent_id'];
            
            $this->question_id = $data['question_id'];

            $this->option_id = $data['option_id'];

            if (isset($data['created_at'])) {
                $this->created_at = $data['created_at'];
            }
            
            if (isset($data['deleted_at'])) {
                $this->deleted_at = $data['deleted_at'];
            }
        }
    }
}