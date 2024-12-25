<?php

namespace App\Entity;

use App\Music\Match\MusicMatchInterface;
use App\Music\Search\MusicSearch;
use App\Repository\ShareRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: ShareRepository::class)]
#[ORM\Index(columns: ['artist', 'album'])]
#[ORM\Index(columns: ['artist', 'album', 'title'])]
class Share
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    public ?Ulid $id;

    #[ORM\Column(enumType: ShareTypeEnum::class)]
    public ?ShareTypeEnum $type = ShareTypeEnum::TRACK;

    #[ORM\Column(length: 1023)]
    public ?string $artist = null;

    #[ORM\Column(length: 1023, nullable: true)]
    public ?string $album = null;

    #[ORM\Column(length: 1023, nullable: true)]
    public ?string $title = null;

    #[ORM\Column(length: 1023, nullable: true)]
    public ?string $thumbnail = null;

    /**
     * @var Collection<int, ShareMatch>
     */
    #[ORM\OneToMany(targetEntity: ShareMatch::class, mappedBy: 'share', cascade: ['persist'], orphanRemoval: true)]
    public Collection $matches;

    /**
     * @param MusicMatchInterface[] $matches
     */
    public function __construct(MusicSearch $search, array $matches)
    {
        $this->type = 'track' === $search->type ? ShareTypeEnum::TRACK : ShareTypeEnum::ALBUM;
        $this->artist = $search->artist ?? null;
        $this->title = $search->title ?? null;
        $this->album = $search->album ?? null;
        $this->thumbnail = $search->thumbnail ?? null;

        $this->matches = new ArrayCollection();
        foreach ($matches as $match) {
            $this->addMatch(new ShareMatch($match));
        }
    }

    public function addMatch(ShareMatch $match): static
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->share = $this;
        }

        return $this;
    }

    public function removeMatch(ShareMatch $match): static
    {
        $this->matches->removeElement($match);

        return $this;
    }
}
