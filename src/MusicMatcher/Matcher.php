<?php

namespace App\MusicMatcher;

use App\MusicProvider\MusicProviderInterface;
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
     * @return MatchInterface[]
     */
    public function match(Search $search): array
    {
        $matches = [];
        foreach ($this->providers as $provider) {
            $matches[] = $provider->match($search);
        }

        return $matches;
    }
}
