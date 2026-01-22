<?php 

namespace App\Core;


use App\Core\Views;
use App\Support\Menssage;

class Controller 
{
    protected Views $views;
    protected Menssage $message;
    public function __construct(string $pathView)
    {
        $this->views = new Views($pathView);
        $this->message = new Menssage();

    }
}