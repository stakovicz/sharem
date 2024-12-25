<?php

namespace App\Music\Provider;

use App\Music\Match\MusicMatchInterface;
use App\Music\Match\MusicMusicMatch;
use App\Music\Search\MusicSearch;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Spotify implements MusicProviderInterface
{
    public function __construct(
        #[Autowire(env: 'SPOTIFY_CLIENT_ID')]
        private readonly string $clientId,
        #[Autowire(env: 'SPOTIFY_CLIENT_SECRET')]
        private readonly string $clientSecret,
    ) {
    }

    private const string NAME = 'Spotify';

    public function match(MusicSearch $search): ?MusicMatchInterface
    {
        $session = new Session(
            $this->clientId,
            $this->clientSecret
        );
        $session->requestCredentialsToken();
        $api = new SpotifyWebAPI(session: $session);

        /** @var object{
         *     tracks: mixed
         * } $results
         */
        $results = $api->search($search->title, $search->type, ['limit' => 1]);

        return new MusicMusicMatch(self::NAME, $results->tracks->items[0]->external_urls->spotify);
    }

    public function url(string $url): ?MusicSearch
    {
        return null;
    }
}
