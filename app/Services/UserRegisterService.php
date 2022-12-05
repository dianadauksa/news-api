<?php

namespace App\Services;

use Doctrine\DBAL\{DriverManager, Connection};

class UserRegisterService
{
    private Connection $connection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => "{$_ENV["DB_NAME"]}",
            'user' => "{$_ENV["DB_USER"]}",
            'password' => "{$_ENV["DB_PASS"]}",
            'host' => "{$_ENV["DB_HOST"]}",
            'driver' => 'pdo_mysql',
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function execute(RegisterServiceRequest $request): void
    {
        $this->connection->insert('Users', [
                'name' => $request->getName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword()]
        );
    }

    public function checkIfRegistered(string $email): ?RegisterServiceRequest
    {
        $check = $this->connection->executeQuery(
            "SELECT * FROM Users WHERE email= ?", [$email]
        )->fetchAssociative();
        if (!$check) {
            return null;
        }
        return new RegisterServiceRequest($check['name'], $check['email'], $check['password']);
    }

    public function findID(string $email): ?int
    {
        $id = $this->connection->executeQuery(
            "SELECT id FROM Users WHERE email= ?", [$email]
        )->fetchAssociative()["id"];
        if (!$id) {
            return null;
        }
        return $id;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}