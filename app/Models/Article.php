<?php

namespace App\Models;

use Carbon\Carbon;

class Article
{
    private string $title;
    private string $publishedAt;
    private string $url;
    private string $urlToImage;

    public function __construct(string $title, string $publishedAt, string $url, string $urlToImage)
    {
        $this->title = $title;
        $this->publishedAt = $publishedAt;
        $this->url = $url;
        $this->urlToImage = $urlToImage;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): string
    {
        return Carbon::createFromDate($this->publishedAt)->format('d/m/y h:i');
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUrlToImage(): ?string
    {
        return $this->urlToImage;
    }
}