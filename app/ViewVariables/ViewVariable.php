<?php

namespace App\ViewVariables;

interface ViewVariable
{
    public function getName(): string;

    public function getValue(): array;
}