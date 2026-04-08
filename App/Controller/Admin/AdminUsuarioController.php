<?php

namespace App\Controller\Admin;


use App\Core\Controller;
use App\Core\Upload;
use App\Models\User;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

class AdminUsuarioController extends Controller
{


    protected User $model;

    public function __construct()
    {
        parent::__construct('App/Themes/Blog/admin/views/');
        $this->model = new User();
    }

    public function listar()
    {
        $user = $this->model->order('level DESC, status ASC')->find();

     $userLoged = (new UserController())->userLogged();

    $cropper = new Cropper("App/Themes/Blog/admin/assets/images/avatar/cache");

    $base = "App/Themes/Blog/admin/assets/images/avatar";
    $default = "{$base}/undefined.png";

    foreach ($user as $u) {

        $path = "{$base}/{$u->avatar}";
        if (
            empty($u->avatar) ||
            !file_exists($path) ||
            !pathinfo($path, PATHINFO_EXTENSION)
        ) {
            $path = $default;
        }

        $thumb = $cropper->make($path, 40, 40);

        // ✅ valida antes de usar
        if ($userLoged && $u->id == $userLoged->id) {
            $userLoged->avatar = $thumb;
        }

        $u->thumb = $thumb;
    }
      
       //var_dump($userLoged);
      // exit;
        $dados = [
            "user" => $user,
            "userLogged" => $userLoged,
            "total" => [
                "user" => $this->model->total(),
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

        if (!empty($dados)) {
            $user = (new User())->findByEmail($dados['email']);

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


    public function editar($id)
    {
        $user = (new User())->findByid($id);
        $dados = [
            "userEdite" => $user
        ];
        echo $this->views->render('usuarios/formulario.html', $dados);
    }


    public function update($id)
    {
        $update = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($update) {
            $user = (new User())->findByid($id);

            if (!$user) {
                $this->message->error("Usuario nao encontrado")->flash();
                Helpers::redirect("/admin/usuario/listar");
                return;
            }

            if (!empty($_FILES['imagem']['name'])) {

                $imagemUpload = $_FILES['imagem']['name'];
                $pathoriiginal = "/App/Themes/Blog/admin/assets/images/avatar/{$user->avatar}";
                if (file_exists($pathoriiginal)) {
                    unlink($pathoriiginal);
                }
                $cropper = new Cropper("App/Themes/Blog/admin/assets/images/avatar/cache");
                $cropper->flush($user->avatar);
                $cropper->make("/App/Themes/Blog/admin/assets/images/avatar/{$imagemUpload}", 40, 40);
                $upload = new Upload();
                $img = $upload->uploadImage($_FILES, "avatar");
                $update['avatar'] = $img;
            }

            (new User())->update($update, "id = {$id}");
            $this->message->success("Usuario atualizado com sucesso")->flash();
            Helpers::redirect('/admin/usuario/listar');
            return;
        }

        $this->message->success("erro ao atualizar usuario")->flash();
        Helpers::redirect('/admin/usuario/editar/{$id}');
    }


    public function deletar($id)
    {
        $user = (new User())->findByid($id);
        if (!$user) {
            $this->message->error('Erro nao existe usuario')->flash();
            Helpers::redirect('/admin/usuario/listar');
            return;
        }
        if ($user) {
            (new User())->delete("id = $id");
            $this->message->success('Usuario deletado com sucesso')->flash();
            Helpers::redirect('/admin/usuario/listar');
            return;
        }
    }
}
