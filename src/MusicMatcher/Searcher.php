<?php

namespace App\MusicMatcher;

use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Searcher
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /** @return SearchResult[] */
    public function search(string $query): array
    {
        $url = 'https://api.deezer.com/search?q=%s&strict=on';
        $response = $this->httpClient->request('GET', sprintf($url, $query));

        $results = [];
        foreach ($response->toArray()['data'] as $data) {
            $results[] = new SearchResult($query, $data);
        }

        return $results;
    }
}
