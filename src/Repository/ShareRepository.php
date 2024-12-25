<?php

namespace App\Repository;

use App\Entity\Share;
use App\Music\Search\MusicSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Share>
 */
class ShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Share::class);
    }

    public function findOneBySearch(MusicSearch $search): ?Share
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.artist = :artist')
            ->setParameter('artist', $search->artist)
            ->andWhere('s.album = :album')
            ->setParameter('album', $search->album);

        if ($search->title) {
            $qb->andWhere('s.title = :title')
                ->setParameter('title', $search->title);
        }

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
