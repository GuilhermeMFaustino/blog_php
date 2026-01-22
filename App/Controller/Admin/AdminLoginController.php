<?php 


namespace App\Controller\Admin;

use App\Core\Controller;

class AdminLoginController extends Controller
{

    public function __construct()
    {
         return parent::__construct('App/Themes/Blog/admin/views/');
    }

    public function login()
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        var_dump($dados);
        die();

        echo $this->views->render('login.html',[]);
    }

    private function checkDados()
    {
        
    }
}