<?php

namespace App\Support;

class Menssage
{
    private $text;

    private $type;

    private $icon;

    
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
        $this->icon = '<i class="fa-solid fa-xmark"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function warning(string $mensagem): Menssage
    {
        $this->type = 'message warning';
        $this->icon = '<i class="fa-solid fa-exclamation"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }

    public function info(string $mensagem): Menssage
    {
        $this->type = 'message info';
        $this->icon = '<i class="fa-solid fa-info"></i>';
        $this->text = $this->filtrar($mensagem);
        return $this;
    }
}
