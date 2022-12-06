<?php

namespace App\Services;

use App\Database;

class UpdateUserService
{
    public function execute(string $type, string $newValue, string $id): void
    {
        $connection = Database::getConnection();
        $connection->executeQuery("UPDATE Users SET {$type} = '{$newValue}' WHERE id = '{$id}'");
    }
}