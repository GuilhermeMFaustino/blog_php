<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Cidades;
use App\Models\Jogos;
use App\Models\Times;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

class AdminTimesController extends Controller
{
    public function __construct()
    {

        parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $times = (new Times())->find();


        $cropper = new Cropper("App/Themes/Blog/admin/assets/images/time/cache");

        $base = "App/Themes/Blog/admin/assets/images/time";
        $default = "{$base}/undefined.png";

        foreach ($times as $u) {

            $path = "{$base}/{$u->imagem_time}";
            if (
                empty($u->imagem_time) ||
                !file_exists($path) ||
                !pathinfo($path, PATHINFO_EXTENSION)
            ) {
                $path = $default;
            }

            $thumb = $cropper->make($path, 40, 40);

            $u->thumb = $thumb;
        }
        //
        $userLogged = (new UserController())->userLogged();
        $dados = [
            "userLogged" => $userLogged,
            "times" => $times,
        ];

        echo $this->views->render("Times/Times.html", $dados);
    }


    public function cadastrar()
    {
        $cidades = (new Cidades())->find();

        $dados = [

            "city" => $cidades
        ];

        echo $this->views->render("Times/formulario.html", $dados);
    }


    public function save()
    {
        $dadosTime = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!array_filter($dadosTime)) {
            $this->message->error("Favor preencher todos os Dados")->flash();
            Helpers::redirect('/admin/times/cadastrar');
            return;
        }
        /*$timeExistente = (new Times())->find("time = :time", "time={$dadosTime['time']}");
        if ($timeExistente) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect('/admin/times/cadastrar');
            return;
        }*/


        // Upload imagem
        if (empty($_FILES['imagem']['name'])) {
            $this->message->error("precisa de uma imagem")->flash();
            Helpers::redirect('/admin/time/cadastrar');
            return;
        }
        if (!empty($_FILES['imagem']['name'])) {

            $ext = strtolower(
                pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION)
            );

            $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidos)) {
                $this->message->error('Arquivo não permitido')->flash();
                echo $this->views->render('time/formulario.html', []);
                return;
            }

            $pasta = 'App/Themes/Blog/admin/assets/images/time/';

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
                echo $this->views->render('time/formulario.html', []);
                return;
            }

            $dadosTime['imagem_time'] = $nome;
        }


        (new Times())->save($dadosTime);
        $this->message->success("Time Cadastrado com sucesso!")->flash();
        Helpers::redirect('/admin/times/listar');
        return;
    }


    public function editar($id)
    {
        $searchTimes = (new Times())->findByid("$id");
        $dados = [
            "timesEdit" => $searchTimes
        ];

        echo $this->views->render("Times/formulario.html", $dados);
    }


    public function update($id)
    {
        $iputedit = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $updateTimes = (new Times())->findByid($id);
        var_dump($updateTimes);
        if (!$updateTimes) {
            $this->message->error("Não existe dados para atalização")->flash();
            Helpers::redirect("/admin/times/editar/{$id}");
            return;
        }

        $time = trim($iputedit['time']);

        $timeExistente = (new Times())->find(
            "time='{$time}' AND id != {$id}"
        );
        var_dump($timeExistente);
        //die();
        if ($timeExistente) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect("/admin/times/editar/{$id}");
            return;
        }
        if ($updateTimes) {
            (new Times())->update($iputedit, "id = {$id}");
            $this->message->success('Time Atualizado com sucesso.')->flash();
            Helpers::redirect("/admin/times/listar");
            return;
        }
    }


    public function deletar($id)
    {
        $serachDelete = (new Times())->findByid($id);
        $searcheJogos = (new Jogos())->find("timeum = :id OR timedois = :id",  "*",   "id={$serachDelete->id}");
        if($searcheJogos){
            $this->message->error("Não é possível excluir este time, pois existem jogos vinculados a ele. Exclua primeiro os jogos associados para prosseguir.")->flash();
            Helpers::redirect("/admin/times/listar");
            return;
        }
        if (!$serachDelete) {
            $this->message->error("Não existe Times para deletar")->flash();
            Helpers::redirect("/admin/times/deletar/{$id}");
            return;
        }

        if ($serachDelete) {
            //(new Times())->delete("id = $id");
            $this->message->success("Time Deletado com sucesso")->flash();
            Helpers::redirect("/admin/times/listar");
            return;
        }
    }
}
