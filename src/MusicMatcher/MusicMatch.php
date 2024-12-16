<?php

namespace App\MusicMatcher;

readonly class MusicMatch implements MatchInterface
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
