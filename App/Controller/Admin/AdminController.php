<?php 


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Session\Session;
use App\Models\Posts;

class AdminController extends Controller
{
     

    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');

       
    }
    public function index()
    {

        $posts = (new Posts());
         $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "total" => [
                'total' => $posts->total(),
                'ativo' => $posts->total('status = 1'),
                'inativo' => $posts->total('status = 0')
            ]
        ];
        echo $this->views->render('index.html', $dados);
    }
}