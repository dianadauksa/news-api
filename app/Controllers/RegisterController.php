<?php

namespace App\Controllers;

use App\Database;
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
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userExists = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchOne(); //returns id => user with such email (user row) exists in database already and has unique id

        // TODO: Move validation into an object (Errors collection), pass the collection to ErrorViewVariables
        if (strlen($_POST['name']) < 3) {
            $_SESSION['errors']['name'] = 'Name must be at least 3 characters long';
        }
        if ($userExists) {
            $_SESSION['errors']['email'] = 'Email address already registered, try logging in';
        }
        if (strlen($_POST['password']) < 6) {
            $_SESSION['errors']['password'] = 'Password must be at least 6 characters long';
        }
        if ($_POST['password'] !== $_POST['password_repeat']) {
            $_SESSION['errors']['password_repeat'] = 'Passwords do not match';
        }
        if (!empty ($_SESSION['errors'])) {
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            )
        );
        return new Redirect('/login');
    }
}
