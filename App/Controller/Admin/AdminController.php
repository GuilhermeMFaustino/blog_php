<?php 


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Posts;
use App\Support\Helpers;

class AdminController extends Controller
{
     

    //protected $user = false;

    public function __construct()
    {
        $user = false;
        
         if(!$user){
            //$this->message->error('teste');
             Helpers::redirect('/admin/login');
         }
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