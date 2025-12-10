<?php

namespace App\Controller;

use App\Core\Controller;

class WebController extends Controller
{
    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/Web/Views/');
    }
    public function index(){
        $dados = [
            "titulo" => 'Pagina Index',
            "produtos" => 'produtos'
        ];
        echo $this->views->render('index.html', $dados);
    }
}