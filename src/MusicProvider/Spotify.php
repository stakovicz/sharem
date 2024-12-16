<?php

namespace App\MusicProvider;

use App\MusicMatcher\MatchInterface;
use App\MusicMatcher\MusicMatch;
use App\MusicMatcher\Search;
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

    public function match(Search $search): ?MatchInterface
    {
        $session = new Session(
            $this->clientId,
            $this->clientSecret
        );
        $session->requestCredentialsToken();
        $api = new SpotifyWebAPI(session: $session);
        $results = $api->search($search->title, $search->type, ['limit' => 1]);

        return new MusicMatch(self::NAME, $results->tracks->items[0]->external_urls->spotify);
    }
}
