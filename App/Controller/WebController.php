<?php

namespace App\Controller;

use App\Core\Controller;
use App\Models\Category;
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
        $category = (new Category())->findByCategory();     
       
        //var_dump($category);

        $dados = [
            "titulo" => 'OnlineBlog',
            "posts" => $posts,
            "category" => $category
        ];
        echo $this->views->render('index.html', $dados);
    }

    public function post(int $id)
    {
        $product = (new Posts())->findByid($id);
        //var_dump($product);

        $dados = [
            "titulo" => 'Sobre',
            "product" => $product
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
