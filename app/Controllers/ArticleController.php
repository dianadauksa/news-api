<?php

namespace App\Controllers;

use App\SeeAllArticles;
use App\View;

class ArticleController
{
    private SeeAllArticles $allArticles;

    public function __construct(SeeAllArticles $allArticles)
    {
        $this->allArticles = $allArticles;
    }

    public function index(): View
    {
        $category = $_GET["category"] ?? "general";

        return new View("articles", ["articles" => $this->allArticles->execute($category)->getAll()]);
    }
}