<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\RegisterServiceRequest;
use App\Services\RegisterService;
use App\View;

class RegisterController
{

    public function index(): View
    {
        return new View("register");
    }

    public function register(): Redirect
    {
        // TODO: Create validation
        $user = (new RegisterService())->checkIfRegistered(
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)
        );
        if (
            $user
            || htmlspecialchars($_POST['password']) !== htmlspecialchars($_POST['password-repeat'])
        ) {
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                $_POST['name'],
                $_POST['email'], //filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            )
        );
        return new Redirect('/login');
    }
}
