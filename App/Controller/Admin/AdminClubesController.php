<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Cidades;
use App\Models\Times;
use App\Support\Helpers;

class AdminClubesController extends Controller
{
    public function __construct()
    {

        parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $times = (new Times())->find();
        
         $userLogged = (new UserController())->userLogged();
        $dados = [
            "userLogged" => $userLogged,
            "times" => $times,
        ];

        echo $this->views->render("clubes/clubes.html", $dados);
    }


    public function cadastrar()
    {
        $cidades = (new Cidades())->find();

        $dados = [

            "city" => $cidades
        ];

        echo $this->views->render("clubes/formulario.html", $dados);
    }


    /*public function save()
    {
        $dadosTime = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!array_filter($dadosTime)) {
            $this->message->error("Favor preencher todos os Dados")->flash();
            Helpers::redirect('/admin/times/cadastrar');
            return;
        }
        $timeExistente = (new Times())->find("time = :time", "time={$dadosTime['time']}");
        if ($timeExistente) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect('/admin/times/cadastrar');
            return;
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
        if (!$updateTimes) {
            $this->message->error("Não existe dados para atalização")->flash();
            Helpers::redirect("/admin/times/editar/{$id}");
            return;
        }

        $timeExistente = (new Times())->find("time = :time", "time={$iputedit['time']}");
       
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
        if (!$serachDelete) {
            $this->message->error("Não existe Times para deletar")->flash();
            Helpers::redirect("/admin/times/deletar/{$id}");
            return;
        }

        if ($serachDelete) {
            (new Times())->delete("id = $id");
            $this->message->success("Time Deletado com sucesso")->flash();
            Helpers::redirect("/admin/times/listar");
            return;
        }
    }*/
}
