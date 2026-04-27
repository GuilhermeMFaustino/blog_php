<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Views;
use App\Core\Visits;
use App\Models\Category;
use App\Models\Clubes;
use App\Models\Jogos;
use App\Models\Posts;
use App\Models\Videos;
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

        $visitas = (new Visits())->views();
        $visitas = (new Visits())->updateOnline();
        $videos = (new Videos())->limit(2)->find();
        $jogos = (new Jogos())->limit(4)->serarchTimeCity();
        $clubes = (new Clubes())->limit(6)->findClubes();

    
        
        foreach ($posts as $post) {
            $cropper = new Cropper(
                "App/Themes/Blog/admin/assets/images/posts/cache"
            );

            $post->thumb = $cropper->make(
                "App/Themes/Blog/admin/assets/images/posts/{$post->posts_imagem}",
                365,
                220
            );
        }


        foreach ($jogos as $jogo) {
            $cropper = new Cropper(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/cache"
            );

            $jogo->thumb = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$jogo->imagemTime}",
                100,
                100
            );

            $jogo->thumb2 = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$jogo->imagem}",
                100,
                100
            );
            $jogo->thumb = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $jogo->thumb
            );

            $jogo->thumb2 = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $jogo->thumb2
            );
        }

        
        foreach ($clubes as $clube) {
            $cropper = new Cropper(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/cache"
            );
           
            $clube->thumb = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$clube->imagem}",
                100,
                100
            );

            $clube->thumb = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $clube->thumb
            );
        };

        $dados = [
            "titulo" => 'OnlineBlog',
            "visitas" => $visitas,
            "posts" => $posts,
            "category" => $category,
            "videos" => $videos,
            "jogos" => $jogos,
            "clubes" => $clubes
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
