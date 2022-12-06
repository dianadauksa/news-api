<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\View;

class LoginController
{
    public function index(): View
    {
        return new View("login");
    }

    public function login(): Redirect
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchAssociative();
        // TODO: Move validation into an object (Errors collection), pass the collection to ErrorViewVariables
        if (!$userData) {
            $_SESSION['errors']['email'] = 'Email address not registered, try to register first';
        }

        if ($userData && !password_verify($_POST['password'], $userData['password'])) {
            $_SESSION['errors']['password'] = 'Incorrect password';
        }

        if (!empty ($_SESSION['errors'])) {
            return new Redirect('/login');
        }

        if (password_verify($_POST['password'], $userData['password'])) {
            $_SESSION["auth_id"] = $userData['id'];;
            return new Redirect('/');
        }
        return new Redirect('/login');
    }

    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        return new Redirect('/login');
    }
}

