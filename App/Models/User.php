<?php


namespace App\Models;

use App\Controller\Admin\UserController;
use App\Core\Models;
use CoffeeCode\Cropper\Cropper;

class User extends Models
{
    protected string $table = 'user';

    protected $model;

    
    public function searchUser(array $dados)
    {
        return $this->findByEmail($dados['email']);
    }
}
