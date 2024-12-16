<?php

namespace App\MusicMatcher;

final class Search
{
    public string $type = '';
    public string $title = '';
    public string $album = '';
    public string $artist = '';
    public string $hash = '';

    public function __construct(?SearchResult $search = null, ?string $hash = null)
    {
        if ($search) {
            $this->type = $search->type;
            $this->title = $search->title;
            $this->album = $search->album;
            $this->artist = $search->artist;
            $this->encode();
        }
        if ($hash) {
            $search = $this->decode($hash);
            $this->type = $search->type;
            $this->title = $search->title;
            $this->album = $search->album;
            $this->artist = $search->artist;
        }
    }

    private function encode(): void
    {
        $this->hash = base64_encode(json_encode($this, JSON_THROW_ON_ERROR));
    }

    private function decode(string $hash): mixed
    {
        return json_decode(base64_decode($hash), false);
    }
}
