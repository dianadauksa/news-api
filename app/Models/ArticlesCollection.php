<?php

namespace App\Models;

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

    public function getAll(): array
    {
        return $this->articles;
    }
}