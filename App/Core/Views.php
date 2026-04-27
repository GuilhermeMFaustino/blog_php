<?php


namespace App\Core;

use App\Controller\Admin\UserController;
use Twig\Lexer;
use Twig\TwigFunction;
use App\Support\Helpers;

class Views extends Controller
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

    public function render(string $view, array $dados = []): string
    {
        // verifica se a view existe
        if (!$this->twig->getLoader()->exists($view)) {
           return "A Template View '{$view}' não foi encontrada.";           
        }

        return $this->twig->render($view, $dados);
    }

    /**
     * Summary of helpers | configura as funcoes para ser exibidas no site.
     * @return void
     */

    public function helpers(): void
    {
        $this->twig->addFunction(
            new TwigFunction('url', function (?string $url = null) {
                return Helpers::url($url);
            })
        ); 
        
        $this->twig->addFunction(
            new TwigFunction('strLmWords', function ($texto, ?string $strLmWords = null) {
                return Helpers::strLmWords($texto, $strLmWords);
            })
        );

        $this->twig->addFunction(
            new TwigFunction('flash', function () {
                return Helpers::flash();
            })
        );

        $this->twig->addFunction(
            new TwigFunction('user', function () {
                return UserController::user();
            })
        );

         $this->twig->addFunction(
            new TwigFunction('data', function () {
                return Helpers::dataAtual();
            })
        );

        $this->twig->addFunction(
            new TwigFunction('formatData', function ($data) {
                return Helpers::formatData($data);
            })
        );

       
    }
}
