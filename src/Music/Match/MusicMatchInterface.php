<?php

namespace App\Music\Match;

interface MusicMatchInterface
{
    public function getMusicProvider(): string;

    public function getUrl(): string;
}
