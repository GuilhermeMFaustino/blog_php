<?php 

namespace App\Core;


use App\Core\Views;

class Controller 
{
    protected Views $views;
    public function __construct(string $pathView)
    {
        $this->views = new Views($pathView);
    }
}