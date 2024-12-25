<?php

namespace App\Entity;

enum ShareTypeEnum: string
{
    case TRACK = 'track';
    case ALBUM = 'album';
    case ARTIST = 'artist';
}
