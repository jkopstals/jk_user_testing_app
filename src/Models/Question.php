<?php
namespace App\Models;

class Question extends Model
{
    public $id;
    public $test_id;
    public $uuid;
    public $description;
    public $created_at;
    public $deleted_at;
    public $sequence;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->id = $data['id'];
            }

            if (isset($data['test_id'])) {
                $this->test_id = $data['test_id'];
            }
            
            if (isset($data['uuid'])) { 
                $this->uuid = $data['uuid'];
            } else {
                $this->uuid = uniqid();
            }
            
            $this->description = $data['description'];

            if (isset($data['created_at'])) {
                $this->created_at = $data['created_at'];
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