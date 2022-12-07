<?php

namespace App\ViewVariables;

class UpdateProfileVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'success';
    }

    public function getValue(): array
    {
        return $_SESSION['success'] ?? [];
    }
}