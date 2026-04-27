<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Cidades;
use App\Models\Jogos;
use App\Models\Times;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

class AdminJogosController extends Controller
{


    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {

        $userLogged = (new UserController())->userLogged();

        $jogos = (new Jogos())->serarchTimeCity();

        foreach ($jogos as $jogo) {
            $cropper = new Cropper(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/cache"
            );

            $jogo->thumb = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$jogo->imagemTime}",
                40,
                40
            );

            $jogo->thumb2 = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$jogo->imagem}",
                40,
                40
            );
            $jogo->thumb = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $jogo->thumb
            );

            $jogo->thumb2 = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $jogo->thumb2
            );
        }



        //var_dump($jogos);
        //die();
        $dados = [
            "userLogged" => $userLogged,
            "jogos" => $jogos
        ];


        echo $this->views->render("jogos/jogos.html", $dados);
    }

    public function cadastrar()
    {

        $userLogged = (new UserController())->userLogged();

        $times = (new Times())->find();

        $cidades = (new Cidades())->find();

        $dados = [
            "userLogged" => $userLogged,
            "times" => $times,
            "cidades" => $cidades
        ];
        echo $this->views->render("jogos/formulario.html", $dados);
    }

    public function save()
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //$cidadesJogos = (new Jogos())->serarchTimeCity();
        if (!array_filter($dados)) {
            $this->message->error("favor preencher todos os campos ")->flash();
            Helpers::redirect("/admin/jogos/cadastrar");
            return;
        }
        $camposObrigatorios = [
            'timeUm',
            'cityUm',
            'timeDois',
            'cityDois',
            'rodada',
            'hora'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                $this->message->error("Favor preencher todos os campos obrigatórios.")->flash();
                Helpers::redirect("/admin/jogos/cadastrar");
                return;
            }
        }
        if ($dados) {                        //die();
            (new Jogos())->save($dados);
            $this->message->success("Jogos cadastrado com sucesso")->flash();
            Helpers::redirect("/admin/jogos/listar");
            return;
        }
        $this->message->error("Erro ao cadastrar o jogo.")->flash();
        Helpers::redirect("/admin/jogos/cadastrar");
        return;
    }

    public function editar($id)
    {
        $times = (new Times())->find();

        $cidades = (new Cidades())->find();

        $editJogo = (new Jogos())->serarchJogoCity($id);
        //var_dump($editJogo);
        //die();
        $dados = [
            "times" => $times,
            "cidades" => $cidades,
            "editJogo" => $editJogo
        ];

        echo $this->views->render("jogos/formulario.html", $dados);
    }

    public function update($id) {

       $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!array_filter($dados)) {
            $this->message->error("favor preencher todos os campos ")->flash();
            Helpers::redirect("/admin/jogos/editar/{$id}");
            return;
        }
        $camposObrigatorios = [
            'timeUm',
            'cityUm',
            'timeDois',
            'cityDois',
            'rodada',
            'hora'
        ];

        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                $this->message->error("Favor preencher todos os campos obrigatórios.")->flash();
                Helpers::redirect("/admin/jogos/editar/{$id}");
                return;
            }
        }
        if ($dados) {        
            var_dump($dados);
           // die();                //die();
            (new Jogos())->update($dados, "id = {$id}");
            $this->message->success("Jogos editado com sucesso")->flash();
            Helpers::redirect("/admin/jogos/listar");
            return;
        }

         $this->message->error("Erro ao atualizar dados do jogo.")->flash();
        Helpers::redirect("/admin/jogos/editar/{$id}");
        return;

    }

    public function deletar($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            Helpers::redirect('/admin/jogos/listar');
        }
        $jogo = new Jogos();

        if (!$jogo->findById($id)) {
            $this->message->warning('O jogo que você está tentando excluir não existe')->flash();
            Helpers::redirect('/admin/jogos/listar');
        }

        if (!$jogo->delete("id = {$id}")) {
            $this->message->error($jogo->error())->flash();
            Helpers::redirect('/admin/jogos/listar');
        }

        $this->message->success('Jogo deletado com sucesso')->flash();
        Helpers::redirect('/admin/jogos/listar');
    }
}
