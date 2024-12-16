<?php

namespace App\MusicMatcher;

final readonly class SearchResult
{
    public string $name;
    public string $url;
    public string $type;
    public string $thumbnail;
    public string $title;
    public string $album;
    public string $artist;
    public string $hash;

    /**
     * @param mixed[] $data
     */
    public function __construct(public string $query, array $data)
    {
        $this->name = $data['title'];
        $this->url = $data['link'];
        $this->type = $data['type'];
        $this->thumbnail = $data['album']['cover_medium'];
        $this->title = $data['title'];
        $this->album = $data['album']['title'];
        $this->artist = $data['artist']['name'];

        $search = new Search($this);
        $this->hash = $search->hash;
    }
}
