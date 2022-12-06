<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\RegisterService;
use App\View;

class LoginController
{

    public function index(): View
    {
        return new View("login");
    }

    public function login(): Redirect
    {
        // TODO: Create validation

        $user = (new RegisterService())->checkIfRegistered(
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
        );

        if (!$user) {
            return new Redirect('/login');
        }

        if (password_verify(htmlspecialchars($_POST['password']), $user->getPassword())) {
            $_SESSION["auth_id"] = (new RegisterService())->findID(
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

