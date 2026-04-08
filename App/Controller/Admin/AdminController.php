<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Core\Session;
use App\Models\Posts;
use App\Support\Helpers;
use App\Controller\Admin\UserController;
use App\Models\User;
use CoffeeCode\Cropper\Cropper;

class AdminController extends Controller
{

    protected $user;
    protected User $model;
    public function __construct()
    {

        parent::__construct('App/Themes/Blog/admin/views/');
        $this->model = new User();

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

        $userLoged = (new UserController())->userLogged();

        $dados = [
            "titulo" => 'Admin - OnlineBlog',
            "userLogged" => $userLoged,
            "total" => [
                'total' => $posts->total(),
                'ativo' => $posts->total('status = 1'),
                'inativo' => $posts->total('status = 0')
            ]
        ];
        echo $this->views->render('index.html', $dados);
    }
}
