<?php

namespace App\NewsRequest;

use App\Models\ArticlesCollection;

interface ShowNews
{
    public function getAll(string $category = "general"): ArticlesCollection;
}