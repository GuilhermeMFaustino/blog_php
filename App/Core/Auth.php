<?php


namespace App\Core;

use App\Models\User;
use App\Support\Menssage;

class Auth extends Models
{

    protected $message;
    protected User $user;
    protected $name;

    public function __construct()
    {
        $this->message = new Menssage();
        $this->user = new User();
    }

    public function login(array $dados)
    {
        if(!$this->checkDados($dados)){
            return false;
        }
    }

    private function checkDados(array $dados): bool
    {       
        // Verifica o e-mail
        if (empty($dados['email'])) {
            $this->message->warning('Campo e-mail é obrigatório')->flash();
            return false;
        }

        // Verifica a senha 
        if (empty($dados['password'])) {
            $this->message->warning('Campo senha é obrigatório')->flash(); 
            return false;
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $this->message->error('O e-mail informado não é válido')->flash();
            return false;
        }
        $user = $this->user->findByEmail($dados['email']);
        if (!$user) {
            $this->message->error('Dados Incorretos ')->flash();
            return false;
        }
        if($dados['email'] != $user->email){
            $this->message->error('Dados Incorretos ')->flash();
            return false;
        }
        if($dados['password'] != $user->password){
            $this->message->error('Dados Incorretos ')->flash();
            return false;
        }
        (new Session())->create('user', $user->id);
        $this->message->success("Seja bem vindo {$user->name}")->flash();
        return true;
    }

    public function getCheckDados(array $dados): bool
    {
        if(empty($dados)){
            return false;
        }
        return $this->checkDados($dados);
    }
}
