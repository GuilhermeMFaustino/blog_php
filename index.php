<?php


use Pecee\SimpleRouter\SimpleRouter;

require 'vendor/autoload.php';

/** */
SimpleRouter::setDefaultNamespace('App\Controller');

SimpleRouter::get("blog/", 'WebController@index');
SimpleRouter::get("blog/sobre", 'SobreController@sobre');

SimpleRouter::start();