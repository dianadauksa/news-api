<?php

namespace App;

use App\NewsRequest\ShowNews;
use App\Models\ArticlesCollection;

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