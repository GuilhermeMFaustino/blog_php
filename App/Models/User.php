<?php


namespace App\Models;

use App\Core\Models;

class User extends Models
{
    protected string $table = 'user';

    public function searchUser(array $dados)
    {
        return $this->findByEmail($dados['email']);
    }
}
