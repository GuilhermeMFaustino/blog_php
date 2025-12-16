<?php

namespace App\Controller;

use App\Core\Controller;
use App\Models\Posts;
use App\Support\Menssage;

class WebController extends Controller
{


    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/Web/Views/');
    }
    public function index()
    {
        $posts = (new Posts())->find();
        $dados = [
            "titulo" => 'OnlineBlog',
            "posts" => $posts
        ];
        echo $this->views->render('index.html', $dados);
    }

    public function post($id)
    {
        $posts = (new Posts())->findByid($id);
        $dados = [
            "titulo" => 'Sobre',
            "post" => $posts
        ];
        echo $this->views->render('post.html', $dados);       
    }

    public function sobre()
    {
        $dados = [
            "titulo" => 'Sobre',
            "produtos" => 'produtos'
        ];
        echo $this->views->render('sobre.html', $dados);
    }

    public function erro404()
    {
        $msg = new Menssage();
        $json = $msg->info('
                        A página que você procura pode ter sido removida, renomeada ou estar temporariamente indisponível.
                ')->render();
        $dados = [
            "titulo" => '404',
            "json_data" => $json
        ];
        echo $this->views->render('404.html', $dados);
    }
}
