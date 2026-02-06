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
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ($dados) {
            $user = $this->user->findByEmail("{$dados['email']}");
            if ($user) {
                $this->message->error('E-mail já cadastrado')->flash();
                Helpers::redirect('/admin/usuario/cadastrar');
            }

            if ($_FILES) {
                $ext = strtolower(
                    pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION)
                );
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($ext, $permitidos)) {
                    $this->message->error('arquivo nao permitido');
                }
                $pasta = 'App/Themes/Blog/Web/assets/images/blog/uploads/';
                if (!is_dir($pasta)) {
                    mkdir($pasta, 0777, true);
                }
                if (!empty($_FILES['imagem']['name'])) {
                    $img = $_FILES['imagem'];
                    $tmp = $img['tmp_name'];
                    $nome = uniqid() . '-' . $img['name'];
                    $destino = $pasta . $nome;

                    if (!move_uploaded_file($tmp, $destino)) {
                        $this->message->error('falha ao mover o arquivo')->flash();                       
                    } 
                    $dados['avatar'] = $nome;                   
                    (new User())->save($dados);
                    Helpers::redirect('/admin/usuario/listar');
                }
            }
           
        }
        echo $this->views->render('usuarios/formulario.html', []);
    }
}
