<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Models\Videos;
use App\Support\Helpers;

class AdminVideosController extends Controller
{
    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/admin/views/');
    }
    public function listar()
    {
        $videos = (new Videos())->find();
        $userLogged = (new UserController())->userLogged();

        $dados = [
            "userLogged" => $userLogged,
            "videos" => $videos
        ];

        echo $this->views->render("videos/videos.html", $dados);
    }

    public function cadastrar()
    {

        echo $this->views->render('videos/formulario.html', []);
    }

    public function save()
    {
        $videos = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        var_dump($videos);
        //exit;

        if (!array_filter($videos)) {
            $this->message->error("erro precisa de um link de video")->flash();
            Helpers::redirect("/admin/videos/cadastrar");
            return;
        }
        $videosSearch = (new Videos())->find("video = '{$videos['video']}'"
        );
        if ($videosSearch) {
            $this->message->error('Video ja publicado')->flash();
            Helpers::redirect("/admin/videos/cadastrar");
        }
        (new Videos())->save($videos);
        $this->message->success('Video cadastrado com sucesso');
        Helpers::redirect("/admin/videos/listar");
    }


    public function editar($id)
    {
        $dadosEdit = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $videoList = (new Videos())->findByid($id);
        if (!$videoList) {
            $this->message->error('Nenhum vídeo encontrado.')->flash();
            Helpers::redirect('/admin/videos/listar');
            return;
        }
        if ($dadosEdit) {
            (new Videos())->update($dadosEdit, "id = {$id}");
            $this->message->success('video atualizado com sucesso')->flash();
            Helpers::redirect("/admin/videos/listar/{$id}");
        }
        $dados = [
            "videosEdit" => $videoList
        ];
        echo $this->views->render('videos/formulario.html', $dados);
    }


    public function deletar($id)
    {
        $videodelet = (new Videos())->findByid($id);
        if (!$videodelet) {
            $this->message->error('Nenhum vídeo encontrado.')->flash();
            Helpers::redirect('/admin/videos/listar');
            return;
        }
        if ($videodelet) {
            (new Videos())->delete("id = {$id}");
            $this->message->success('video deletado com sucesso.')->flash();
            Helpers::redirect('/admin/videos/listar');
            return;
        }
        var_dump($videodelet);
        exit;
    }
}
