<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Posts;
use App\Support\Helpers;

class AdminPostsController extends Controller
{


    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $posts = (new Posts());
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "posts" => $posts->find()
            
        ];
        echo $this->views->render('posts/posts.html', $dados);
    }

    public function cadastrar()
    {
        $posts = filter_input_array(INPUT_POST, FILTER_DEFAULT);   

        $categorias = (new Category())->find();

        if (!empty($posts)) {
            (new Posts())->save('posts', $posts);
            $this->menssage->success('Post Cadastrado com sucesso')->flash();
            Helpers::redirect('/admin/posts/listar');
            return;            
        }

        $dados = [
                "titulo" => 'Admin - OnlineBlog',
                "categorias" => $categorias
            ];

        echo $this->views->render('posts/formulario.html', $dados);
    }


    public function editar(int $id): void
    {
        $post = (new Posts())->findByid($id);
        $categorias = (new Category())->find();
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new Posts())->update($dados, $id);            
            Helpers::redirect('/admin/posts/listar');
        }

        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "editarposts" => $post,
            "categorias" => $categorias
        ];
        echo $this->views->render('posts/formulario.html', $dados);
    }


    public function deletar(int $id): void
    {
        $id = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($post)) {
            (new Posts())->delete( $id);
            Helpers::redirect('/admin/posts/listar');
        }
    }
}
