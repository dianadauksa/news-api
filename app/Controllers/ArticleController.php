<?php

namespace App\Controllers;

use App\Services\SeeAllArticlesService;
use App\View;

class ArticleController
{
    private SeeAllArticlesService $allArticles;

    public function __construct(SeeAllArticlesService $allArticles)
    {
        $this->allArticles = $allArticles;
    }

    public function index(): View
    {
        $category = $_GET["category"] ?? "coding";

        return new View("articles", ["articles" => $this->allArticles->execute($category)->getArticles()]);
    }
}