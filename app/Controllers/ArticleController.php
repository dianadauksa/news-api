<?php

namespace App\Controllers;

use App\Services\ShowAllArticlesService;
use App\View;

class ArticleController
{

    public function index(): View
    {
        $category = $_GET["category"] ?? "coding";
        $articles = (new ShowAllArticlesService())->execute($category);

        return new View("articles", ["articles" => $articles->getArticles()]);
    }
}