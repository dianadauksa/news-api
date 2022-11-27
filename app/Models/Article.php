<?php

namespace App\Models;

class Article
{
    private string $title;
    private string $description;
    private string $url;
    private string $urlToImage;

    public function __construct(string $title, string $description, string $url, string $urlToImage)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->urlToImage = $urlToImage;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUrlToImage(): string
    {
        return $this->urlToImage;
    }
}