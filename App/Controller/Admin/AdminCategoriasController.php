<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Posts;
use App\Models\User;
use App\Support\Helpers;

class AdminCategoriasController extends Controller
{
   
    public function __construct()
    {      
        return parent::__construct('App/Themes/Blog/admin/views/');
    }

    public function listar()
    {
        $categories = (new Category())->find();
        $userLoged = (new UserController())->userLogged();

        $dados = [
           "userLogged" => $userLoged,
            "titulo" => 'Admin - OnlineBlog',
            "categoria" => $categories
        ];
        echo $this->views->render('categoria/categoria.html', $dados);
    }


    public function cadastrar()
    {

        $categories = (new Category())->find();
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "categorias" => $categories
        ];
        echo $this->views->render('categoria/formulario.html', $dados);
    }

    public function save()
    {

        $categorias = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!array_filter($categorias)) {

            $this->message->error('favor preencher os campos')->flash();
            Helpers::redirect('/admin/categorias/cadastrar');
            return;
        }

        (new Category())->save($categorias);
        $this->message->success('Dados Cadastrado com sucesso')->flash();
        Helpers::redirect('/admin/categorias/listar');
        return;
    }

    public function editar(int $id): void
    {
        $categoria = (new Category())->findById($id);
       

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new Category())->update($dados, "id = {$id}");
            $this->message->success(mensagem: 'categorias editadas com sucesso')->flash();
            Helpers::redirect('/admin/categorias/listar');
        }

        $dados = [
            "titulo" => 'Editar - OnlineBlog',
            "editarcategoria" => $categoria
        ];

        echo $this->views->render('categoria/formulario.html', $dados);
    }

    public function deletar(int $id): void
    {
        $id = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        var_dump($id);
        if (isset($id)) {
            (new Category())->delete("id = $id");
            Helpers::redirect('/admin/categorias/listar');
        }
    }
}
