<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/participant')]
class ParticipantController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    // Injection de la dépendance security dans le constructeur
    #[Route('/', name: 'app_participant_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_participant_show', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function show(Participant $participant,
                         SortieRepository $sortieRepository): Response
    {
        $utilisateurActuel = $this->security->getUser();

        // Affichage de la liste des sorties dont l'utilisateur est organisateur :
        // vérification de si l'utilisateur est l'organisateur
        if ($utilisateurActuel === $participant) {
            // Si oui : la page affichera toutes les sorties sans distinction
            $sorties = $sortieRepository->findBy(['organisateur' => $participant->getId()]);
        } else {
            // Si non : la page n'affichera que les sorties publiées
            $queryBuilder = $sortieRepository->createQueryBuilder('s')
                ->where('s.organisateur = :organisateur AND s.etat <> 1')
                ->setParameter('organisateur', $participant)
                ->getQuery();
            $sorties = $queryBuilder->getResult();

        }

        // Affichage de la liste des sorties dont l'utilisateur est participant
        $sortiesInscrit = $participant->getEstInscrit()->toArray();

        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
            'sorties' => $sorties,
            'sortiesInscrit' => $sortiesInscrit
        ]);


    }

    #[Route('/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
