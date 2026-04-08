<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use CoffeeCode\Cropper\Cropper;

class UserController extends Controller
{

    protected User $model;

    public function __construct()
    {
        $this->model = new User();
        return parent::__construct('App/Themes/Blog/Web/Views/');
    }


    public static function user()
    {
        $session = new Session();

        if (!$session->check('user')) {
            return null;
        }

        return (new User())->findByid($session->user);
    }

    public function userLogged()
{
    $userLoged = $this->user;

    if (!$userLoged) {
        return null;
    }

    $cropper = new Cropper("App/Themes/Blog/admin/assets/images/avatar/cache");

    $base = "App/Themes/Blog/admin/assets/images/avatar";
    $default = "{$base}/undefined.png";

    $path = "{$base}/{$userLoged->avatar}";

    if (
        empty($userLoged->avatar) ||
        !file_exists($path) ||
        !pathinfo($path, PATHINFO_EXTENSION)
    ) {
        $path = $default;
    }

    $userLoged->avatar = $cropper->make($path, 40, 40);

    return $userLoged;
}
}
