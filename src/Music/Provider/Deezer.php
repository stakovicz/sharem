<?php

namespace App\Music\Provider;

use App\Music\Match\MusicMatchInterface;
use App\Music\Match\MusicMusicMatch;
use App\Music\Search\MusicSearch;
use App\Music\Search\SearchResult;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Deezer implements MusicProviderInterface
{
    private const string API_URL = 'https://api.deezer.com';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    private const string NAME = 'Deezer';

    public function match(MusicSearch $search): ?MusicMatchInterface
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

        $url = '%s/search?q=%s';
        $response = $this->httpClient->request('GET', sprintf($url, self::API_URL, urlencode($queryString)));

        return new MusicMusicMatch(self::NAME, $response->toArray()['data'][0]['link']);
    }

    public function url(string $url): ?MusicSearch
    {
        $url = self::removeQS($url);

        // https://deezer.page.link/AqR3WdsSiDn19WaW9
        $regexp = '#https://deezer\.page\.link/([a-z0-9]+)$#i';
        if (preg_match($regexp, $url, $matches)) {
            $request = $this->httpClient->request('GET', $url, ['max_redirects' => 0]);
            $url = self::removeQS($request->getHeaders(false)['location'][0]);
        }

        $regexp = '#https://www\.deezer\.com/(.+/)?(track|album)/([0-9]+)$#';

        if (preg_match($regexp, $url, $matches)) {
            $request = $this->httpClient->request('GET', sprintf('%s/%s/%d', self::API_URL, $matches[2], $matches[3]));

            $search = new SearchResult($request->toArray());

            return new MusicSearch($search);
        }

        return null;
    }

    private static function removeQS(string $url): string
    {
        $parts = parse_url($url);
        if (!isset($parts['scheme'], $parts['host'], $parts['path'])) {
            return $url;
        }

        return $parts['scheme'].'://'.$parts['host'].$parts['path'];
    }
}
