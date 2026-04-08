<?php 

namespace App\Core;


use App\Controller\Admin\UserController;
use App\Core\Views;
use App\Support\Menssage;

class Controller
{
    protected Views $views;
    protected Menssage $message;
    protected $user;
    public function __construct(string $pathView)
    {
        $this->views = new Views($pathView);
        $this->message = new Menssage();
        $this->user = UserController::user();
    }
}