<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Core\Session;
use App\Models\Posts;
use App\Support\Helpers;
use App\Controller\Admin\UserController;

class AdminController extends Controller
{

    protected $user;


    public function __construct()
    {

        parent::__construct('App/Themes/Blog/admin/views/');

        $this->user = UserController::user();
        //var_dump(session_status());
        //var_dump(session_status());
        //var_dump($_SESSION);
        //var_dump($this->user);
        //exit;
        if (!$this->user or $this->user->level != 1) {
            $this->message->error('Para acesso ao painel de controle voce precisa esta logado')->flash();
            $session = new Session();
            $session->clean('user');
            Helpers::redirect('/admin/login')->flash();
        }
    }
    public function index()
    {       
        $posts = (new Posts());
        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "total" => [
                'total' => $posts->total(),
                'ativo' => $posts->total('status = 1'),
                'inativo' => $posts->total('status = 0'),
                'user' => $this->user->name
            ]
        ];
        echo $this->views->render('index.html', $dados);
    }
}
