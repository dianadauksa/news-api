<?php

namespace App\Models\Collections;

use App\Models\Article;

class ArticlesCollection
{
    private array $articles = [];

    public function __construct(array $articles)
    {
        foreach ($articles as $article) {
            $this->add($article);
        }
    }

    private function add(Article $article): void
    {
        $this->articles[] = $article;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }
}