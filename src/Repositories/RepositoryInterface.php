<?php 

namespace App\Repositories;

use \App\Database;

interface RepositoryInterface
{
    public function __construct(Database $db);

    public function find($id);

    public function findAll();
}