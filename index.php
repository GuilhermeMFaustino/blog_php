<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Support\Helpers;

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
    SimpleRouter::get("blog/post/{id}", 'WebController@post');


    SimpleRouter::post("blog/buscar", 'WebController@buscar');


    /**Redirecionamento 404 */
    SimpleRouter::get('blog/404', 'WebController@erro404');    

    SimpleRouter::start();

} catch (Exception $e) {
    if(Helpers::localhost()) {
        echo $e->getMessage();
    } else {
        Helpers::redirect('404');
    }
}
