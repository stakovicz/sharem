<?php

namespace App\Controller;

use App\MusicMatcher\Searcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, Searcher $repository): Response
    {
        $query = (string) $request->query->get('query');
        if ($query) {
            $results = $repository->search($query);
        }

        $form = $this->searchForm($query);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('app_home', ['query' => $data['query']]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results ?? [],
        ]);
    }

    private function searchForm(?string $query): FormInterface
    {
        return $this->createFormBuilder()
            ->add('query', TextType::class, [
                'label' => 'Search label',
                'attr' => ['placeholder' => 'Search placeholder'],
                'data' => $query,
                'label_attr' => ['class' => 'visually-hidden'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => '🔎',
            ])
            ->getForm();
    }
}
