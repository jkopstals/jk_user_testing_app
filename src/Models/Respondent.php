<?php
namespace App\Models;

class Respondent extends Model
{
    public $id;
    public $test_id;
    public $uuid;
    public $name;
    public $created_at;
    public $completed_at;
    public $deleted_at;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->id = $data['id'];
            }
            
            if (isset($data['uuid'])) { 
                $this->uuid = $data['uuid'];
            } else {
                $this->uuid = uniqid();
            }
            
            $this->name = $data['name'];

            $this->test_id = $data['test_id'];

            if (isset($data['created_at'])) {
                $this->created_at = $data['created_at'];
            }
            
            if (isset($data['completed_at'])) {
                $this->completed_at = $data['completed_at'];
            }
            
            if (isset($data['deleted_at'])) {
                $this->deleted_at = $data['deleted_at'];
            }
            
        }
    }
}