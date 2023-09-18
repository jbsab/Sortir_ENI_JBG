<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'sortir_main')]
    public function index(SortieRepository $sortieRepository): Response
    {

        $queryBuilder = $sortieRepository->createQueryBuilder('s')
            ->where('s.etat <> 1')
            ->getQuery();
        $sorties = $queryBuilder->getResult();

        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('security/admin.html.twig');
    }
}
