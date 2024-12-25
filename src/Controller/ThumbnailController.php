<?php

namespace App\Controller;

use App\Repository\ShareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThumbnailController extends AbstractController
{
    public function __construct(private readonly ShareRepository $shareRepository,
        #[Autowire(param: 'public_dir')]
        private readonly string $publicDir)
    {
    }

    #[Route('/thumbnail/{id}', name: 'app_thumbnail')]
    public function index(string $id): Response
    {
        $path = "/thumbnails/$id.jpg";
        $file = $this->publicDir.$path;

        if (is_file($file)) {
            return $this->redirect($path, Response::HTTP_PERMANENTLY_REDIRECT);
        }

        if (!$share = $this->shareRepository->findOneBy(['id' => $id])) {
            throw $this->createNotFoundException(sprintf('Share with id "%s" not found', $id));
        }

        file_put_contents($file, file_get_contents($share->thumbnail));

        return $this->redirect($path, Response::HTTP_PERMANENTLY_REDIRECT);
    }
}
