<?php 


namespace App\Core;

use Twig\Lexer;
use Twig\TwigFunction;
use App\Support\Helpers;


class Views
{
    private $twig;

    /**
     * Summary of __construct
     * @param string $views
     */
    public function __construct(string $folder)
    {
       $loader = new \Twig\Loader\FilesystemLoader($folder);
       $this->twig = new \Twig\Environment($loader);

       $lexer = new Lexer($this->twig, array($this->helpers()));
       $this->twig->setLexer($lexer);
    }

    public function render(string $view, array $dados): string
    {
        return $this->twig->render($view,$dados);
    }

    public function helpers()
    {
        array(
            $this->twig->addFunction(
                new TwigFunction('url', function(string $url = null){
                    return Helpers::saudacoes();
                })

        ));
    }

}