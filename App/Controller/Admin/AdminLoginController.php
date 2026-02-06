<?php


namespace App\Controller\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Support\Helpers;

class AdminLoginController extends Controller
{

    protected $user;

    public function __construct()
    {
        parent::__construct('App/Themes/Blog/admin/views/');
        $this->user = UserController::user();
        
    }

    public function login()
    { 
        $dadosInput = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ($dadosInput) {
            if (empty(array_filter($dadosInput))) {
                $this->message->error('Campos em branco')->flash();
            } else {
                $auth = new Auth();
                if ($auth->getCheckDados($dadosInput)) {                    
                   Helpers::redirect('/admin');
                }else{
                    Helpers::redirect('/admin/login');
                }
            }
        }
        $dados = [
            "titulo" => "Admin"
        ];

        echo $this->views->render('login.html', $dados);
    }


    public function logout(): void
    {
        $user = UserController::user();
        $this->message->info("Volte Sempre! {$user->name}")->flash();
        $session = new Session();
        $session->clean('user');
        Helpers::redirect('/admin/login');
        
    }
}
