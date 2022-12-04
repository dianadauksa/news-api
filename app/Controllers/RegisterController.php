<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\RegisterServiceRequest;
use App\Services\UserRegisterService;
use App\View;

class RegisterController
{

    public function index(): View
    {
        return new View("register");
    }

    public function register(): Redirect
    {
        $user = (new UserRegisterService())->checkIfRegistered(
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
        );
        if (
            $user
            || htmlspecialchars($_POST['password']) !== htmlspecialchars($_POST['password-repeat'])
        ) {
            return new Redirect('/register');
        }

        $registerService = new UserRegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                htmlspecialchars($_POST['name']),
                filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT)
            )
        );
        return new Redirect('/login');
    }
}
