<?php

namespace App\NewsRequest;

use App\Models\Article;
use App\Models\ArticlesCollection;
use GuzzleHttp\Client;

class NewsApiRequest implements ShowNews
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            "base_uri" => $_ENV["NEWS_API_URL"]
        ]);
    }

    public function getAll(string $category = "general"): ArticlesCollection
    {
        $url = "top-headlines?country=us&category=$category&apiKey={$_ENV["NEWS_API_KEY"]}";

        $apiResponse = json_decode($this->httpClient->get($url)->getBody()->getContents());

        $articles = [];
        foreach ($apiResponse->articles as $article) {
            $articles[] = new Article(
                (string)$article->title,
                (string)$article->description,
                (string)$article->url,
                (string)$article->urlToImage
            );
        }
        return new ArticlesCollection($articles);
    }
}