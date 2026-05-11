<?php


namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Cidades;
use App\Models\Clubes;
use App\Models\Times;
use App\Support\Helpers;
use CoffeeCode\Cropper\Cropper;

class AdminClubesController extends Controller
{
    public function __construct()
    {

        parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $userLogged = (new UserController())->userLogged();

        $findCidades = (new Clubes())->findClubes();

        //var_dump($findCidades);
        //die();
        foreach ($findCidades as $jogo) {
            $cropper = new Cropper(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/cache"
            );

            $jogo->thumb = $cropper->make(
                ROOT . "/App/Themes/Blog/admin/assets/images/time/{$jogo->imagem}",
                50,
                50
            );

            $jogo->thumb = str_replace(
                ROOT,
                URL_DESENVOLVIMENTO,
                $jogo->thumb
            );
        }

        $dados = [
            "userLogged" => $userLogged,
            "findCidades" => $findCidades,

        ];

        echo $this->views->render("clubes/clubes.html", $dados);
    }


    public function cadastrar()
    {
        $cidades = (new Cidades())->find();

        $userLogged = (new UserController())->userLogged();

        $dados = [
            "city" => $cidades,
            "userLogged" => $userLogged
        ];

        echo $this->views->render("clubes/formulario.html", $dados);
    }


    public function save()
    {
        $dadosClubes = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!array_filter($dadosClubes)) {
            $this->message->error("Favor preencher todos os Dados")->flash();
            Helpers::redirect('/admin/clubes/cadastrar');
            return;
        }
        $clubeExistente = (new Clubes())->find("name = :name", ['name' => $dadosClubes['name']]);

        if ($clubeExistente) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect('/admin/clubes/cadastrar');
            return;
        }
        (new Clubes())->save($dadosClubes);
        $this->message->success("Time Cadastrado com sucesso!")->flash();
        Helpers::redirect('/admin/clubes/listar');
        return;
    }


    public function editar($id)
    {
        $searchClubes = (new Clubes())->findByid("$id");

        $city = (new Cidades())->findByidCity($id);

        $dados = [
            "clubesEdit" => $searchClubes,
            "city" => $city
        ];

        echo $this->views->render("clubes/formulario.html", $dados);
    }


    public function update($id)
    {
        $iputedit = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $updateTimes = (new Clubes())->findByid($id);
        if (!$updateTimes) {
            $this->message->error("Não existe dados para atalização")->flash();
            Helpers::redirect("/admin/clubes/editar/{$id}");
            return;
        }

        $clubeExistente = (new Clubes())->find("name = :name", ['name' => $iputedit['name']]);

        if ($clubeExistente) {
            $this->message->error("Time já Cadastrado")->flash();
            Helpers::redirect("/admin/clubes/editar/{$id}");
            return;
        }
        if ($updateTimes) {
            (new Clubes())->update($iputedit, "id = {$id}");
            $this->message->success('Time Atualizado com sucesso.')->flash();
            Helpers::redirect("/admin/clubes/listar");
            return;
        }
    }


    public function delete($id)
    {
        $serachDelete = (new clubes())->findByid($id);
        var_dump($serachDelete);
        if (!$serachDelete) {
            $this->message->error("Não existe Clubes para deletar ou ocorreu um erro!.")->flash();
            Helpers::redirect("/admin/clubes/delete/{$id}");
            return;
        }

        if ($serachDelete) {
            var_dump($serachDelete);
            //die();
            (new Clubes())->delete("id = $id");
            //var_dump($teste);
            //die();
            $this->message->success("Clube Deletado com sucesso")->flash();
            Helpers::redirect("/admin/clubes/listar");
            return;
        }
    }
}
