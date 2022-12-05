<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserRegisterService;
use App\View;

class LoginController
{

    public function index(): View
    {
        return new View("login");
    }

    public function login(): Redirect
    {
        $user = (new UserRegisterService())->checkIfRegistered(
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
        );
        if (!$user) {
            return new Redirect('/login');
        }
        //Salīdzina vai eksistē, sessijās saglabā LIETOTĀJA ID.
        //Izmantojot ID uz pieprasījumu katru reizi pajautā lietotāja datus, izvada uz ekrāna lietotāja vārdu.
        if (password_verify(htmlspecialchars($_POST['password']), $user->getPassword())) {
            $_SESSION["id"] = (new UserRegisterService())->findID(
                filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
            );
            return new Redirect('/');
        }
        return new Redirect('/login');
    }

    public function logout(): Redirect
    {
        session_destroy();
        return new Redirect('/login');
    }
}

