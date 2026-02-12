<?php

namespace App\Support;

use App\Core\Session;

class Menssage
{
    private $text;

    private $type;

    private $icon;
    public function __toString()
    {
        return $this->render();
    }
    
    private function filtrar(string $mensagem)
    {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function render(): string
    {
        return "<div class='{$this->type}'>{$this->icon} {$this->text}</div>";
    }

    public function success(string $mensagem): Menssage
    {
        $this->type = 'message success';
        $this->icon = '<i class="fa-solid fa-check"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function error(string $mensagem): Menssage
    {
        $this->type = 'message error';
        $this->icon = '<i class="fa-solid fa-circle-xmark"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function warning(string $mensagem): Menssage
    {
        $this->type = 'message warning';
        $this->icon = '<i class="fa-sharp fa-solid fa-exclamation"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function info(string $mensagem): Menssage
    {
        $this->type = 'message info';
        $this->icon = '<i class="fa-solid fa-circle-info"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function flash(): void
    {
        $session = new Session();
        $session->create('flash', $this);
    }

    
}
