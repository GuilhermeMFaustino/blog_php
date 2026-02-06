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
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $categorias = (new Category())->find();
        //var_dump($dados);

        if (isset($dados)) {
            //var_dump($dados);
            //die();
            /*$posts = new Posts();
            $posts->title = $dados['title'];
            $posts->categoria_id = $dados['categoria_id'];
            $posts->texto = $dados['texto'];
            $posts->satatus = $dados['sataus'];*/

            (new Posts())->save($dados);
            $this->message->success('Post Cadastrado com sucesso')->flash();
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
            (new Posts())->update($dados, "id = {$id}");
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
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            Helpers::redirect('/admin/posts/listar');
        }
        $post = new Posts();

        if (!$post->findById($id)) {
            $this->message->warning('O post que você está tentando excluir não existe')->flash();
            Helpers::redirect('/admin/posts/listar');
        }

        if (!$post->delete("id = {$id}")) {
            $this->message->error($post->error())->flash();
            Helpers::redirect('/admin/posts/listar');
        }

        $this->message->success('Post deletado com sucesso')->flash();
        Helpers::redirect('/admin/posts/listar');
    }
}
