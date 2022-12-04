<?php

namespace App\Services;

use Doctrine\DBAL\{DriverManager, Connection, Exception};

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
        $check = $this->connection->executeQuery("SELECT * FROM Users WHERE email= '{$email}'")->fetchAssociative();
        if (!$check) {
            return null;
        } else {
            return new RegisterServiceRequest($check['name'], $check['email'], $check['password']);
        }
    }
}