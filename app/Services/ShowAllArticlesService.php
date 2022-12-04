<?php

namespace App\Services;

use App\Models\Collections\ArticlesCollection;
use App\Models\Article;
use GuzzleHttp\Client;

class ShowAllArticlesService
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            "base_uri" => $_ENV["NEWS_API_URL"]
        ]);
    }

    public function execute(string $category): ArticlesCollection
    {
        $url = "everything?q=$category&language=en&sortBy=publishedAt&apiKey={$_ENV["NEWS_API_KEY"]}";

        $apiResponse = json_decode($this->httpClient->get($url)->getBody()->getContents());

        $articles = [];
        foreach ($apiResponse->articles as $article) {
            $articles[] = new Article(
                (string)$article->title,
                (string)$article->publishedAt,
                (string)$article->url,
                (string)$article->urlToImage
            );
        }
        return new ArticlesCollection($articles);
    }
}