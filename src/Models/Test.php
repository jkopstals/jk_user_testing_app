<?php
namespace App\Models;

class Test extends Model
{
    public $id;
    public $uuid;
    public $name;
    public $created_at;
    public $published_at;
    public $deleted_at;
    public $sequence;

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

            if (isset($data['created_at'])) {
                $this->created_at = $data['created_at'];
            }
            
            if (isset($data['published_at'])) {
                $this->published_at = $data['published_at'];
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