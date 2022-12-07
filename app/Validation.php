<?php

namespace App;

class Validation
{
    public function registerValidate(array $post): void
    {
        $this->validateNewName($post);
        $this->validateNewEmail($post);
        $this->validateNewPassword($post);

    }

    public function loginValidate(array $post): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $post['email'])
            ->fetchAssociative();

        if (!$userData) {
            $_SESSION['errors']['email'] = 'Email address not registered, try to register first';
        }

        if ($userData && !password_verify($post['password'], $userData['password'])) {
            $_SESSION['errors']['password'] = 'Incorrect password';
        }
    }

    public function validationFailed(): bool
    {
        return count($_SESSION['errors']) > 0;
    }

    private function validateNewName(array $post): void
    {
        if (strlen($post['name']) < 3) {
            $_SESSION['errors']['name'] = 'Name must be at least 3 characters long';
        }
    }

    private function validateNewEmail(array $post): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userExists = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $post['email'])
            ->fetchOne(); //returns id => user with such email (user row) exists in database already and has unique id

        if ($userExists) {
            $_SESSION['errors']['email'] = 'Email address already registered';
        }
    }

    private function validateNewPassword(array $post): void
    {
        if (strlen($post['password']) < 6) {
            $_SESSION['errors']['password'] = 'Password must be at least 6 characters long';
        }
        if ($post['password'] !== $post['password_repeat']) {
            $_SESSION['errors']['password_repeat'] = 'Passwords do not match';
        }
    }
}