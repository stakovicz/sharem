<?php

namespace App\Controller;

use App\MusicMatcher\Matcher;
use App\MusicMatcher\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShareController extends AbstractController
{
    public function __construct(
        readonly private Matcher $matcher)
    {
    }

    #[Route('/share', name: 'app_share')]
    public function index(Request $request): Response
    {
        $hash = $request->query->get('hash');
        $search = new Search(hash: $hash);

        $matches = $this->matcher->match($search);

        return $this->render('share/index.html.twig', [
            'matches' => $matches,
        ]);
    }
}
