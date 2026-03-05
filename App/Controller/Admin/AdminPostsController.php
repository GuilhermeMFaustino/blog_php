<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Upload;
use App\Models\Category;
use App\Models\Posts;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

class AdminPostsController extends Controller
{


    protected Posts $posts;
    public function __construct()
    {
        $this->posts = new Posts();
        return parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $posts = $this->posts->order('status ASC')->find();

        $cropper = new Cropper("App/Themes/Blog/admin/assets/images/posts/cache");

        $base = "App/Themes/Blog/admin/assets/images/posts";

        $default = "{$base}/undefined.png";


        foreach ($posts as $u) {

            $path = "{$base}/{$u->posts_imagem}";
            if (
                empty($u->posts_imagem) ||
                !file_exists($path) ||
                !pathinfo($path, PATHINFO_EXTENSION)
            ) {
                $path = $default;
            }
            $u->thumb = $cropper->make($path, 40, 40);
        }


        $dados = [
            "posts" => $posts,
            "titulo" => 'Admin - OnlineBlog'
        ];

        echo $this->views->render('posts/posts.html', $dados);
    }

    public function cadastrar()
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($dados) {
            $this->message->error('favor preencher os campos')->flash();
            $upload = new Upload();
            $img = $upload->uploadImage($_FILES, "posts");

            $dados['posts_imagem'] = $img;

            (new Posts())->save($dados);
            $this->message->success('Post Cadastrado com sucesso')->flash();
            Helpers::redirect('/admin/posts/listar');
            echo $this->views->render('posts/formulario.html', []);
            return;
        }

        $categorias = (new Category())->find();

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
        //var_dump($post);
        //die();
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "editarPosts" => $post,
            "categorias" => $categorias
        ];
        echo $this->views->render('posts/formulario.html', $dados);
    }


    public function update(int $id)
    {
        $update = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($update) {
            $posts = (new Posts())->findByid($id);

            if (!$posts) {
                $this->message->error("Usuario nao encontrado")->flash();
                Helpers::redirect("/admin/posts/listar");
                return;
            }

            if (!empty($_FILES['imagem']['name'])) {

                $imagemUpload = $_FILES['imagem']['name'];
                $pathoriiginal = "App/Themes/Blog/admin/assets/images/posts/{$posts->posts_imagem}";
                if (file_exists($pathoriiginal)) {
                    unlink($pathoriiginal);
                }
                $cropper = new Cropper("App/Themes/Blog/admin/assets/images/posts/cache");
                $cropper->flush($posts->posts_imagem);
                $cropper->make("App/Themes/Blog/admin/assets/images/posts/{$imagemUpload}", 40, 40);
                $upload = new Upload();
                $img = $upload->uploadImage($_FILES, "posts");
                $update['posts_imagem'] = $img;
            }

            (new Posts())->update($update, "id = {$id}");
            $this->message->success("Post atualizado com sucesso")->flash();
            Helpers::redirect('/admin/posts/listar');
            return;
        }

        $this->message->success("erro ao atualizar Post")->flash();
        Helpers::redirect('/admin/posts/editar/{$id}');
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
