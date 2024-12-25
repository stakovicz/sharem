<?php

namespace App\Music\Provider;

use App\Music\Match\MusicMatchInterface;
use App\Music\Search\MusicSearch;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.music_provider')]
interface MusicProviderInterface
{
    public function match(MusicSearch $search): ?MusicMatchInterface;

    public function url(string $url): ?MusicSearch;
}
