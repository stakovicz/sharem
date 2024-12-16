<?php

namespace App\MusicProvider;

use App\MusicMatcher\MatchInterface;
use App\MusicMatcher\MusicMatch;
use App\MusicMatcher\Search;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Deezer implements MusicProviderInterface
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    private const string NAME = 'Deezer';

    public function match(Search $search): ?MatchInterface
    {
        $queryString = '';
        if ($search->title) {
            $queryString .= 'track:"'.$search->title.'" ';
        }
        if ($search->album) {
            $queryString .= 'album:"'.$search->album.'" ';
        }
        if ($search->artist) {
            $queryString .= 'artist:"'.$search->artist.'" ';
        }

        $url = 'https://api.deezer.com/search?q=%s';
        $response = $this->httpClient->request('GET', sprintf($url, urlencode($queryString)));

        return new MusicMatch(self::NAME, $response->toArray()['data'][0]['link']);
    }
}
