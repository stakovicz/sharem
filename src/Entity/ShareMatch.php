<?php

namespace App\Entity;

use App\Music\Match\MusicMatchInterface;
use App\Repository\ShareMatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShareMatchRepository::class)]
class ShareMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 255)]
    public string $provider;

    #[ORM\Column(length: 1023)]
    public string $url;

    #[ORM\ManyToOne(inversedBy: 'matches')]
    #[ORM\JoinColumn(nullable: false)]
    public Share $share;

    public function __construct(MusicMatchInterface $match)
    {
        $this->provider = $match->getMusicProvider();
        $this->url = $match->getUrl();
    }
}
