<?php

namespace App\Services;

use App\Database;

class RegisterService
{
    public function execute(RegisterServiceRequest $request): void
    {
        $connection = Database::getConnection();
        $connection->insert('Users', [
                'name' => $request->getName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword()]
        );
    }

    public function checkIfRegistered(string $email): ?RegisterServiceRequest
    {
        $connection = Database::getConnection();
        $check = $connection->executeQuery(
            "SELECT * FROM Users WHERE email= ?", [$email]
        )->fetchAssociative();
        if (!$check) {
            return null;
        }
        return new RegisterServiceRequest($check['name'], $check['email'], $check['password']);
    }

    public function findID(string $email): ?int
    {
        $connection = Database::getConnection();
        $id = $connection->executeQuery(
            "SELECT id FROM Users WHERE email= ?", [$email]
        )->fetchAssociative()["id"];
        if (!$id) {
            return null;
        }
        return $id;
    }
}