<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;


class UserController extends Controller
{


    public function __construct()
    {
        return parent::__construct('App/Themes/Blog/Web/Views/');
    }
   

    public static function user()
    {
        $session = new Session();

        if(!$session->check('user')){
            return null;
        }

        return (new User())->findByid($session->user);
    }
}
