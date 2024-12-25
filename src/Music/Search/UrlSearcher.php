<?php

namespace App\Music\Search;

use App\Music\Provider\MusicProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class UrlSearcher
{
    /**
     * @param MusicProviderInterface[] $providers
     */
    public function __construct(
        #[AutowireIterator('app.music_provider')]
        private iterable $providers,
    ) {
    }

    public function url(string $url): ?MusicSearch
    {
        foreach ($this->providers as $provider) {
            if ($search = $provider->url($url)) {
                return $search;
            }
        }

        return null;
    }
}
