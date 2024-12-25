<?php

namespace App\Music\Search;

use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Searcher
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /** @return SearchResult[] */
    public function search(string $query): array
    {
        $url = 'https://api.deezer.com/search?q=%s&strict=on&order=RANKING';
        $response = $this->httpClient->request('GET', sprintf($url, $query));

        $results = [];
        foreach ($response->toArray()['data'] as $data) {
            $results[] = new SearchResult($data);
        }

        return $results;
    }
}
