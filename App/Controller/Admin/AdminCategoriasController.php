<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Posts;
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
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "categoria" => $categories
        ];
        echo $this->views->render('categoria/categoria.html', $dados);
    }


    public function cadastrar()
    {
        $categorias = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($categorias)) {
            (new Category())->save('category', $categorias);
            Helpers::redirect('admin/categorias/listar');
        }
        $categories = (new Category())->find();
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "categorias" => $categories
        ];
        echo $this->views->render('categoria/formulario.html', $dados);
    }


    public function editar(int $id): void
    {
        $categoria = (new Category())->findById($id);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new Category())->update($dados, $id);
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
                 
        var_dump($id);
        //$id = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($id)) {
            (new Category())->delete( $id);
             Helpers::redirect('/admin/categorias/listar');
        }
    }
}
