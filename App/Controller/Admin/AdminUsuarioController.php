<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Models\User;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

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
        $user = $this->user->order('level  DESC, status ASC')->find();

        $cropper = new Cropper("App/Themes/Blog/admin/assets/images/avatar/cache");

        $base = "App/Themes/Blog/admin/assets/images/avatar";
        $default = "{$base}/default.png";

        foreach ($user as $u) {

            $path = "{$base}/{$u->avatar}";
            if (
                empty($u->avatar) ||
                !file_exists($path) ||
                !pathinfo($path, PATHINFO_EXTENSION)
            ) {
                $path = $default;
            }
            $u->thumb = $cropper->make($path, 40, 40);
        }

        $dados = [
            "user" => $user,
            "total" => [
                "user" => $this->user->total(),
            ]
        ];
        echo $this->views->render('usuarios/user.html', $dados);
    }

    public function cadastrar()
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];

        
        if (empty($dados)) {
            echo $this->views->render('usuarios/formulario.html', []);
            return;
        }
       
        $dados = array_map('trim', $dados);
       
        if (in_array("", $dados, true)) {
            $this->message->error("Preencha todos os campos")->flash();
            echo $this->views->render('usuarios/formulario.html', []);
            return;
        }
       
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
            Helpers::redirect('/admin/usuario/cadastrar');
            return;
        }
        if (!empty($_FILES['imagem']['name'])) {

            $ext = strtolower(
                pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION)
            );

            $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidos)) {
                $this->message->error('Arquivo não permitido')->flash();
                echo $this->views->render('usuarios/formulario.html', []);
                return;
            }

            $pasta = 'App/Themes/Blog/admin/assets/images/avatar/';

            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            $img  = $_FILES['imagem'];
            $tmp  = $img['tmp_name'];
            $base = pathinfo($img['name'], PATHINFO_FILENAME);
            $base = Helpers::setUri($base);
            $nome = uniqid() . '-' . $base . "." . $ext;
            $destino = $pasta . $nome;

            if (!move_uploaded_file($tmp, $destino)) {
                $this->message->error('Falha ao mover o arquivo')->flash();
                echo $this->views->render('usuarios/formulario.html', []);
                return;
            }

            $dados['avatar'] = $nome;
        }
        
        (new User())->save($dados);

        Helpers::redirect('/admin/usuario/listar');
    }
}
