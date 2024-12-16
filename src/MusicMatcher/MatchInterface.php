<?php

namespace App\MusicMatcher;

interface MatchInterface
{
    public function getMusicProvider(): string;

    public function getUrl(): string;
}
