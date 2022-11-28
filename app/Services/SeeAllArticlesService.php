<?php

namespace App\Services;

use App\Models\ArticlesCollection;
use App\NewsRequest\ShowNews;

class SeeAllArticlesService
{
    private ShowNews $allNews;

    public function __construct(ShowNews $allNews)
    {
        $this->allNews = $allNews;
    }

    public function execute(string $category): ArticlesCollection
    {
        return $this->allNews->getAll($category);
    }
}