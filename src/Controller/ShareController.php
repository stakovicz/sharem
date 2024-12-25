<?php

namespace App\Controller;

use App\Entity\Share;
use App\Music\Match\Matcher;
use App\Music\Search\MusicSearch;
use App\Music\Search\SearchException;
use App\Repository\ShareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShareController extends AbstractController
{
    public function __construct(
        readonly private Matcher $matcher,
        readonly private EntityManagerInterface $em,
        readonly private ShareRepository $shareRepository)
    {
    }

    #[Route('/s/{id}', name: 'app_share')]
    public function share(Share $share): Response
    {
        return $this->render('share.html.twig', ['share' => $share]);
    }

    #[Route('/create/{hash}', name: 'app_create')]
    public function create(string $hash): Response
    {
        try {
            $search = new MusicSearch(hash: $hash);
        } catch (SearchException $e) {
            throw $this->createNotFoundException($e->getMessage(), $e);
        }

        if ($share = $this->shareRepository->findOneBySearch($search)) {
            return $this->redirectToRoute('app_share', ['id' => $share->id]);
        }

        $matches = $this->matcher->match($search);

        $share = new Share($search, $matches);
        $this->em->persist($share);
        $this->em->flush();

        return $this->redirectToRoute('app_share', ['id' => $share->id]);
    }
}
