<?php

namespace App\Music\Match;

use App\Music\Provider\MusicProviderInterface;
use App\Music\Search\MusicSearch;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class Matcher
{
    /**
     * @param MusicProviderInterface[] $providers
     */
    public function __construct(
        #[AutowireIterator('app.music_provider')]
        private iterable $providers,
    ) {
    }

    /**
     * @return MusicMatchInterface[]
     */
    public function match(MusicSearch $search): array
    {
        $matches = [];
        foreach ($this->providers as $provider) {
            $matches[] = $provider->match($search);
        }

        return $matches;
    }
}
