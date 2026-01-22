<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Support\Helpers;                    
try {
    require 'vendor/autoload.php';

    $requestUri = $_SERVER['REQUEST_URI'];

    if (strpos($requestUri, '/App/Themes/') !== false) {
        return false; // deixa o Apache servir
    }

    /**Web**/
    SimpleRouter::setDefaultNamespace('App\Controller');

    SimpleRouter::get("blog/", 'WebController@index');
    SimpleRouter::get("blog/sobre", 'WebController@sobre');
    SimpleRouter::get("blog/post/{id}", 'WebController@post');


    SimpleRouter::post("blog/buscar", 'WebController@buscar');



    /**Admin */

    SimpleRouter::group(['namespace' => 'Admin'], function(){

        /**AdminLogin */
         SimpleRouter::match(['get', 'post'], 'blog/admin/login', 'AdminLoginController@login');

        SimpleRouter::get('blog/admin/', 'AdminController@index');


        /**Posts */
        SimpleRouter::get('blog/admin/posts/listar', 'AdminPostsController@listar');
        

        /**Categoria*/
        SimpleRouter::get('blog/admin/categorias/listar', 'AdminCategoriasController@listar');

        /**formulario de cadastro post  */
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/cadastrar', 'AdminCategoriasController@cadastrar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/posts/cadastrar', 'AdminPostsController@cadastrar');

        /**editar posts e categoria*/
        SimpleRouter::match(['get', 'post'], 'blog/admin/posts/editar/{id}', 'AdminPostsController@editar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/editar/{id}', 'AdminCategoriasController@editar');

        /**Deletar */
        SimpleRouter::get( 'blog/admin/categorias/deletar/{id}', 'AdminCategoriasController@deletar');
        SimpleRouter::get( 'blog/admin/posts/deletar/{id}', 'AdminCategoriasController@deletar');

    });
    

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
