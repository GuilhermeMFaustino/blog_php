<?php

namespace App\Core;

use App\Support\Helpers;
use App\Support\Menssage;


class Upload
{

    protected $message;

    public function __construct()
    {
        $this->message = new Menssage();
    }
    public function uploadImage(array $file, $diretorio): ?string
    {
        // Upload imagem
        if (empty($file['imagem']['name'])) {
            return null;
        } else {

            //if (!empty($file['imagem']['name'])) {
            $ext = strtolower(
                pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION)
            );

            $permitidos = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $permitidos)) {
                $this->message->error("tipo de arquvivo nao e permitido")->flash();
                return null;            
            }

            $pasta = "App/Themes/Blog/admin/assets/images/{$diretorio}/";
            
            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }else{
                echo 'pasta existe';
            }

            $img  = $file['imagem'];
            $tmp  = $img['tmp_name'];
            $base = pathinfo($img['name'], PATHINFO_FILENAME);
            $base = Helpers::setUri($base);
            $nome = uniqid() . '-' . $base . "." . $ext;
            $destino = $pasta . $nome;

            if (!move_uploaded_file($tmp, $destino)) {
                $this->message->error('Falha ao mover o arquivo')->flash();
                return null;
            }

            return $nome;
        }
    }



}
