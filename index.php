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
        SimpleRouter::get('blog/admin/logout', 'AdminLoginController@logout');


        /**Usuario */
        SimpleRouter::get('blog/admin/usuario/listar', 'AdminUsuarioController@listar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/usuario/cadastrar', 'AdminUsuarioController@cadastrar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/usuario/editar/{id}', 'AdminUsuarioController@editar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/usuario/deletar/{id}', 'AdminUsuarioController@deletar');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/usuario/update/{id}', 'AdminUsuarioController@update');


        /**Videos*/
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/listar', 'AdminVideosController@listar');
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/listar', 'AdminVideosController@listar');
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/cadastrar', 'AdminVideosController@cadastrar');
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/save', 'AdminVideosController@save');
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/editar/{id}', 'AdminVideosController@editar');
       SimpleRouter::match(['get', 'post'], 'blog/admin/videos/deletar/{id}', 'AdminVideosController@deletar');

       
        /**Categoria  */
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/cadastrar', 'AdminCategoriasController@cadastrar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/save', 'AdminCategoriasController@save');
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/editar/{id}', 'AdminCategoriasController@editar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/update/{id}', 'AdminCategoriasController@editar');
        SimpleRouter::match(['get', 'post'], 'blog/admin/categorias/deletar/{id}', 'AdminCategoriasController@deletar');
         SimpleRouter::get('blog/admin/categorias/listar', 'AdminCategoriasController@listar');


         SimpleRouter::match(['get', 'post'], 'blog/admin/posts/cadastrar', 'AdminPostsController@cadastrar');        
         SimpleRouter::match(['get', 'post'], 'blog/admin/posts/editar/{id}', 'AdminPostsController@editar');
         SimpleRouter::match(['get', 'post'], 'blog/admin/posts/update/{id}', 'AdminPostsController@update');
         SimpleRouter::match(['get', 'post'], 'blog/admin/posts/deletar/{id}', 'AdminPostsController@deletar');  
         SimpleRouter::get('blog/admin/posts/listar', 'AdminPostsController@listar');     
         
        /**Time*/ 
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/listar', 'AdminTimesController@listar');        
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/cadastrar', 'AdminTimesController@cadastrar');        
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/save', 'AdminTimesController@save');        
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/editar/{id}', 'AdminTimesController@editar');        
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/update/{id}', 'AdminTimesController@update');        
        SimpleRouter::match(['get', 'post'], 'blog/admin/times/deletar/{id}', 'AdminTimesController@deletar'); 
        
        
        /**Jogos */
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/listar', 'AdminJogosController@listar');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/cadastrar', 'AdminJogosController@cadastrar');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/save', 'AdminJogosController@save');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/editar/{id}', 'AdminJogosController@editar');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/update/{id}', 'AdminJogosController@update');  
        SimpleRouter::match(['get', 'post'], 'blog/admin/jogos/deletar/{id}', 'AdminJogosController@deletar');  


        /**Cidades */
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/listar', 'AdminCidadesController@listar'); 
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/cadastrar', 'AdminCidadesController@cadastrar');
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/save', 'AdminCidadesController@save'); 
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/editar/{id}', 'AdminCidadesController@editar'); 
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/update/{id}', 'AdminCidadesController@update'); 
         SimpleRouter::match(['get', 'post'], 'blog/admin/cidades/deletar/{id}', 'AdminCidadesController@deletar'); 


         /**Clubes */
         SimpleRouter::match(['get', 'post'], 'blog/admin/clubes/listar', 'AdminClubesController@listar'); 
         SimpleRouter::match(['get', 'post'], 'blog/admin/clubes/cadastrar', 'AdminClubesController@cadastrar'); 


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
