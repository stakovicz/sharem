<?php

namespace App\Music\Match;

readonly class MusicMusicMatch implements MusicMatchInterface
{
    public function __construct(private string $provider, private string $url)
    {
    }

    public function getMusicProvider(): string
    {
        return $this->provider;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
