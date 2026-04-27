<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Cidades;
use App\Support\Helpers;

class AdminCidadesController extends Controller

{

    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');
    }

    public function listar()
    {
        $Cidades = (new Cidades())->find();

        $dados = [
            "cidades" =>  $Cidades
        ];

        echo $this->views->render("cidades/cidades.html", $dados);
    }

    public function cadastrar()
    {


        echo $this->views->render("cidades/formulario.html");
    }

    public function save()
    {
        $cidades = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!$cidades) {
            $this->message->error("Favor preencher os campos")->flash();
            Helpers::redirect("/admin/cidades/cadastrar");
            return;
        }
        if ($cidades) {
            (new Cidades())->save($cidades);
            $this->message->success("Cidade cadastrada com sucesso")->flash();
            Helpers::redirect('/admin/cidades/listar');
            return;
        }

        $cityCadastrada = (new Cidades())->find("name = :name", "name={$cidades['name']}");
        if (empty($cityCadastrada)) {
            $this->message->error("Não existe cidades cadastradas")->flash();
            Helpers::redirect("/admin/cidades/cadastrar");
            return;
        } else {
            $this->message->error("Cidade já cadastradas")->flash();
            Helpers::redirect("/admin/cidades/cadastrar");
            return;
        }

        if (!$cityCadastrada) {
            (new Cidades())->save($cidades);
            Helpers::redirect("/admin/cidades/listar");
            $this->message->success("Cidades Cadastrada");
            return;
        }
    }

    public function editar($id)
    {
        $city = (new Cidades())->findByid($id);


        $dados = [
            "city" => $city
        ];

        echo $this->views->render("cidades/formulario.html", $dados);
    }

    public function update($id)
    {
        $iputedit = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $cidadesUpdate = (new Cidades())->findByid($id);

        if (!$cidadesUpdate) {
            $this->message->error("Não existe dados para atulização")->flash();
            Helpers::redirect("/admin/cidades/editar/{$id}");
            return;
        }

        $cidade = trim($iputedit['name']);


        $cidadesExistentes = (new Cidades())->find("name='{$cidade}' AND id != {$id}");

        if ($cidadesExistentes) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect("/admin/cidades/editar/{$id}");
            return;
        }
        if ($cidadesUpdate) {
            (new Cidades())->update($iputedit, "id = {$id}");
            $this->message->success('Cidade Atualizada com sucesso.')->flash();
            Helpers::redirect("/admin/cidades/listar");
            return;
        }
    }


    public function deletar($id)
    {
        $serachDelete = (new Cidades())->findByid($id);
        if (!$serachDelete) {
            $this->message->error("Não existe Times para deletar")->flash();
            Helpers::redirect("/admin/cidades/deletar/{$id}");
            return;
        }

        if ($serachDelete) {
            (new Cidades())->delete("id = $id");
            $this->message->success("Time Deletado com sucesso")->flash();
            Helpers::redirect("/admin/cidades/listar");
            return;
        }
    }
}
