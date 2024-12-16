<?php

namespace App\MusicProvider;

use App\MusicMatcher\MatchInterface;
use App\MusicMatcher\Search;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.music_provider')]
interface MusicProviderInterface
{
    public function match(Search $search): ?MatchInterface;
}
