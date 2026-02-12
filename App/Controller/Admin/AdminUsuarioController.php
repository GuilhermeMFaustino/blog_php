<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Models\User;
use App\Support\Helpers;

class AdminUsuarioController extends Controller
{


    protected User $user;

    public function __construct()
    {
        $this->user = new User();
        parent::__construct('App/Themes/Blog/admin/views/');
    }

    public function listar()
    {



        $dados = [
            "user" => $this->user->order('level  DESC, status ASC')->find(),
            "total" => [
                'user' => $this->user->total(),
            ]
        ];

        echo $this->views->render('usuarios/user.html', $dados);
    }

    public function cadastrar()
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];

        // 1️⃣ Verifica se veio POST
        if (empty($dados)) {
            echo $this->views->render('usuarios/formulario.html', []);
            return;
        }

        // 2️⃣ Remove espaços
        $dados = array_map('trim', $dados);

        // 3️⃣ Valida campos vazios
        if (in_array("", $dados, true)) {
            $this->message->error("Preencha todos os campos")->flash();
            echo $this->views->render('usuarios/formulario.html', []);
            return;
        }

        // 4️⃣ Verifica e-mail existente
        if (!empty($dados['email'])) {
            $user = $this->user->findByEmail($dados['email']);

            if ($user) {
                $this->message->error("E-mail {$user->email} já está cadastrado")->flash();
                Helpers::redirect('/admin/usuario/cadastrar');
                return;
            }
        }

        // Upload imagem
        if (empty($_FILES['imagem']['name'])) {
            $this->message->error("precisa de uma imagem")->flash();
        }
        if (!empty($_FILES['imagem']['name'])) {

            $ext = strtolower(
                pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION)
            );

            $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidos)) {
                $this->message->error('Arquivo não permitido')->flash();
                return;
            }

            $pasta = 'App/Themes/Blog/Web/assets/images/blog/uploads/';

            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            $img  = $_FILES['imagem'];
            $tmp  = $img['tmp_name'];
            $nome = uniqid() . '-' . $img['name'];
            $destino = $pasta . $nome;

            if (!move_uploaded_file($tmp, $destino)) {
                $this->message->error('Falha ao mover o arquivo')->flash();
                return;
            }

            $dados['avatar'] = $nome;
        }

        // 6️⃣ Salva usuário
        (new User())->save($dados);

        Helpers::redirect('/admin/usuario/listar');
    }
}
