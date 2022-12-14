<?php

namespace App\Controllers;

use App\{Database, Redirect, View};
use App\Services\UpdateUserService;

class ProfileController
{
    public function index(): View
    {
        return new View("userprofile");
    }

    public function updateUserInfo(): Redirect
    {
        if (!empty ($_POST['name'])) {
            $this->updateName();
        }
        if (!empty ($_POST['email'])) {
            $this->updateEmail();
        }
        return new Redirect('/userprofile');
    }

    public function changePassword(): Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION["auth_id"])
            ->fetchAssociative();

        if (!password_verify($_POST['old_password'], $userData['password'])) {
            $_SESSION['errors']['old_password'] = 'Current password incorrect';
        }
        if (strlen($_POST['password']) < 6) {
            $_SESSION['errors']['password'] = 'Password must be at least 6 characters long';
        }
        if ($_POST['password'] !== $_POST['password_repeat']) {
            $_SESSION['errors']['password_repeat'] = 'Passwords do not match';
        }
        if (!empty ($_SESSION['errors'])) {
            return new Redirect('/userprofile');
        }

        $newValue = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $id = $_SESSION["auth_id"];
        $updateUserService = new UpdateUserService();
        $updateUserService->execute('password', $newValue, $id);
        $_SESSION['success']['password'] = 'Your password was successfully changed';
        return new Redirect('/userprofile');
    }

    private function updateName(): Redirect
    {
        if (strlen($_POST['name']) < 3) {
            $_SESSION['errors']['name'] = 'Name must be at least 3 characters long';
        }
        if (!empty ($_SESSION['errors'])) {
            return new Redirect('/userprofile');
        }

        $newValue = $_POST['name'];
        $id = $_SESSION["auth_id"];
        $updateUserService = new UpdateUserService();
        $updateUserService->execute('name', $newValue, $id);
        $_SESSION['success']['name'] = 'Your name was successfully updated';
        return new Redirect('/userprofile');
    }

    private function updateEmail(): Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION["auth_id"])
            ->fetchAssociative();

        $emailTaken = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchOne();
        if ($userData['email'] !== $_POST['email'] && $emailTaken) {
            $_SESSION['errors']['email'] = 'Email address already registered for a different user';
        }
        if (!empty ($_SESSION['errors'])) {
            return new Redirect('/userprofile');
        }

        $newValue = $_POST['email'];
        $id = $_SESSION["auth_id"];
        $updateUserService = new UpdateUserService();
        $updateUserService->execute('email', $newValue, $id);
        $_SESSION['success']['email'] = 'Your email was successfully updated';
        return new Redirect('/userprofile');
    }
}
