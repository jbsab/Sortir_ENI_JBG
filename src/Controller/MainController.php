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
        return $this->render('main/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('security/admin.html.twig');
    }
}
