<?php

namespace App\Music\Search;

final class MusicSearch
{
    public string $type = '';
    public string $artist = '';
    public string $album = '';
    public ?string $title = null;
    public string $thumbnail = '';
    public string $hash = '';

    /**
     * @throws SearchException
     */
    public function __construct(?SearchResult $search = null, ?string $hash = null)
    {
        if ($search) {
            $this->complete($search);
            $this->encode();
        }

        if ($hash) {
            $search = $this->decode($hash);
            $this->complete($search);
        }
    }

    /**
     * @throws SearchException
     */
    private function encode(): void
    {
        try {
            $this->hash = base64_encode(json_encode($this, JSON_THROW_ON_ERROR));
        } catch (\JsonException $exception) {
            throw new SearchException('Error encoding hash: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws SearchException
     */
    private function decode(string $hash): mixed
    {
        try {
            return json_decode(base64_decode($hash), false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new SearchException('Error decoding hash: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function complete(mixed $search): void
    {
        $this->type = $search->type;
        $this->artist = $search->artist;
        $this->album = $search->album;
        $this->title = $search->title ?? null;
        $this->thumbnail = $search->thumbnail;
    }
}
