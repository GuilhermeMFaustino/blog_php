<?php

namespace App\Controller;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Posts;
use App\Support\Menssage;
use CoffeeCode\Cropper\Cropper;

class WebController extends Controller
{


    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/Web/Views/');
    }
    public function index()
    {
        $posts = (new Posts())->limit('8')->find();
        $category = (new Category())->findByCategory();

        
        $cropper = new Cropper(
             "App/Themes/Blog/admin/assets/images/posts/cache"
        );


        foreach($posts as $post){

            $post->thumb = $cropper->make(
                  "App/Themes/Blog/admin/assets/images/posts/{$post->posts_imagem}",
                365,
                220
            );
            /*$post->thumb = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $post->thumb
            );*/
        }
        //var_dump($posts);

        $dados = [
            "titulo" => 'OnlineBlog',
            "posts" => $posts,
            "category" => $category
        ];
        echo $this->views->render('index.html', $dados);
    }

    public function post(int $id)
    {
        $product = (new Posts())->findById($id);
        if (!$product) {
            echo "Post não encontrado";
            return;
        }

        $cropper = new Cropper(
            ROOT . "/App/Themes/Blog/admin/assets/images/posts/cache"
        );

        $product->thumb = $cropper->make(
            ROOT . "/App/Themes/Blog/admin/assets/images/posts/{$product->posts_imagem}",
            1200,
            500
        );
        $product->thumb = str_replace(
            ROOT,
            URL_DESENVOLVIMENTO,
            $product->thumb
        );
        $dados = [
            "titulo" => "Sobre",
            "post" => $product
        ];
        echo $this->views->render("post.html", $dados);
    }

    public function buscar(): void
    {
        $pesquisar = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($pesquisar)) {
            $search = (new Posts())->pesquisar($pesquisar['buscar']);
            $dados = [
                "titulo" => 'Sobre',
                "search" => $search
            ];
            echo $this->views->render('buscar.html', $dados);
        }
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
