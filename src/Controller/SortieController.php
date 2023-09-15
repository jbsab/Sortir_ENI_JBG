<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    // Injection de la dépendance security dans le constructeur

    #[Route('/', name: 'app_sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupération du participant connecté
        $participant = $this->security->getUser();

        $sortie = new Sortie();
        $sortie->setNbInscrits(0);
        // On définit le participant connecté actuel comme étant l'organisateur
        $sortie->setOrganisateur($participant);

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('bg-success text-white', 'La sortie a bien été crée.');

            return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('bg-warning text-dark', 'La sortie a bien été modifée');

            return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('bg-danger text-white', 'La sortie a bien été supprimée.');
        }

        return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/inscription', name: 'app_sortie_inscription', methods: ['GET', 'POST'])]
    public function inscription(int $id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $participant = $this->getUser();

        if (!$sortie || !$participant) {
            // Verification de l'existence de la sortie ou du participant
            return new Response('Sortie ou participant non trouvé', 404);
        }

        // Vérification de si l'utilisateur n'est pas déjà inscrit à la sortie
        if (!$sortie->getInscrit()->contains($participant)) {
            $sortie->addInscrit($participant);

            $sortie->setNbInscrits($sortie->getNbInscrits() + 1);

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('bg-success text-white', 'Vous êtes inscrit à cette sortie !');

        } else {

            $this->addFlash('bg-danger text-white', 'Vous êtes déjà inscrit à cette sortie.');
        }
        return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
    }

}
