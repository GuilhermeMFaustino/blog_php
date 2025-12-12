<?php

use App\Support\Helpers;
use Pecee\SimpleRouter\SimpleRouter;
use App\Support\Menssage;
use App\Core\Connect;

try {

    require 'vendor/autoload.php';


    $requestUri = $_SERVER['REQUEST_URI'];

    if (strpos($requestUri, '/App/Themes/') !== false) {
        return false; // deixa o Apache servir
    }


    /** */
    SimpleRouter::setDefaultNamespace('App\Controller');

    SimpleRouter::get("blog/", 'WebController@index');
    SimpleRouter::get("blog/sobre", 'WebController@sobre');



    /**Redirecionamento 404 */
    SimpleRouter::get('blog/404', 'WebController@erro404');

    SimpleRouter::start();
} catch (Exception $e) {
    Helpers::redirect('404');
}


$conn = Connect::getInstance();
var_dump($conn);