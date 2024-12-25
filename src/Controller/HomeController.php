<?php

namespace App\Controller;

use App\Music\Search\UrlSearcher;
use App\Music\Search\Searcher;
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
    public function index(Request $request, Searcher $searcher, UrlSearcher $urlSearcher): Response
    {
        $query = (string) $request->query->get('query');
        if ($query) {
            $results = $searcher->search($query);
        }

        $form = $this->searchForm($query);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (filter_var($data['query'], FILTER_VALIDATE_URL)) {
                if ($search = $urlSearcher->url($data['query'])) {

                    return $this->redirectToRoute('app_create', ['hash' => $search->hash]);
                }
            }
            return $this->redirectToRoute('app_home', ['query' => $data['query']]);
        }

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'results' => $results ?? [],
        ]);
    }

    private function searchForm(?string $query): FormInterface
    {
        return $this->createFormBuilder()
            ->add('query', TextType::class, [
                'label' => 'search.query.label',
                'attr' => ['placeholder' => 'search.query.placeholder'],
                'label_attr' => ['class' => 'visually-hidden'],
                'data' => $query,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'search.submit',
            ])
            ->getForm();
    }
}
