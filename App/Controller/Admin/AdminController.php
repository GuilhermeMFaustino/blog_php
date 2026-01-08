<?php 


namespace App\Controller\Admin;

use App\Core\Controller;

class AdminController extends Controller
{
     

    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function index()
    {
         $dados = [
            "titulo" => 'Admin - OnlineBlog'
        ];
        echo $this->views->render('index.html', $dados);
    }
}