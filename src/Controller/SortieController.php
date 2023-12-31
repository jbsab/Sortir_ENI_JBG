<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FiltersSorties;
use App\Entity\Sortie;
use App\Form\FiltersSortiesType;
use App\Form\SortieType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Clock\now;

#[Route('/')]
class SortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        // rajouter le service des etats
    }
    // Injection de la dépendance security dans le constructeur

    #[Route('/', name: 'sortir_main', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository,
                          Request $request): Response
    {

        /*        // Classe D.T.O.
                $oFilters = new FiltersSorties();

                // On associe la classe de D.T.O. à son formulaire spécifique, ici FiltersSortiesFormType::class
                $form = $this->createForm(FiltersSortiesType::class, $oFilters);
                $form->handleRequest($request);

                // On récupère l'utilisateur connecté
                $oUser = $this->getUser();

                if ($form->isSubmitted() && $form->isValid()) {
                    $sorties = $sortieRepository->findFilteredSorties($oFilters, $oUser);
                }
                else
                {
                    $sorties = $sortieRepository->findCurrentSorties();
                }

                return $this->render('sortie/index.html.twig',[
                    'sorties' => $sorties,
                    'filtersForm' => $form->createView()
                ]); */

      $queryBuilder = $sortieRepository->createQueryBuilder('s')
            ->where('s.etat <> 1')
            ->getQuery();
        $sorties = $queryBuilder->getResult();

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties

        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupération du participant connecté
        $participant = $this->security->getUser();

        $sortie = new Sortie();
        $sortie->setNbInscrits(0);

        // récupération de l'etat avec l'id 1 (créée)
        // définition de l'état par défaut à la creation de la sortie
        $etatCree = $entityManager->getRepository(Etat::class)->find(1);
        $sortie->setEtat($etatCree);

        // On définit le participant connecté actuel comme étant l'organisateur
        $sortie->setOrganisateur($participant);

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);

            // vérifier si le bouton "publier" à été cliqué
            if ($request->request->get('publier') === 'Publier') {
                $etatOuvert = $entityManager->getRepository(Etat::class)->find(2);
                $sortie->setEtat($etatOuvert);
                $this->addFlash('bg-success text-white', 'La sortie a bien été publiée.');
            }elseif ($request->request->get('annuler') === 'Annuler') {
                $etatAnnule = $entityManager->getRepository(Etat::class)->find(6);
                $sortie->setEtat($etatAnnule);
                $this->addFlash('bg-success text-white', 'La sortie a bien été annulée.');
            }
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
    public function show(Sortie $sortie,
                        EntityManagerInterface $entityManager
    ): Response
    {
        // On recupere la liste des inscrits à la sortie actuelle via la méthode
        // getInscrit de l'entité Sortie
        $participantsInscrits = $sortie->getInscrit()->toArray();

        // On vérifie si la date limite d'inscription est passée ou non
        // auquel cas on passe l'état de la sortie en "cloturée"

        if ($sortie->getDateLimiteInscription() <= now()) {
            if ($sortie->getEtat()->getId() !== 3) {

                $etatCloture = $entityManager->getRepository(Etat::class)->find(3);
                $sortie->setEtat($etatCloture);

                $this->addFlash('bg-success text-white', 'Date d\'inscription dépassée - Inscriptions Clotûrées.');

                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        // On vérifie si la date de début est passée ou non
        // auquel cas on passe l'état de la sortie en "Activité en cours"
        if ($sortie->getDateHeureDebut() <= now()) {
            if ($sortie->getEtat()->getId() !== 4) {
                $etatEnCours = $entityManager->getRepository(Etat::class)->find(4);
                $sortie->setEtat($etatEnCours);

                $this->addFlash('bg-success text-white', 'Inscriptions terminées - L\'activité est en cours');

                $entityManager->persist($sortie);
                $entityManager->flush();
            }

            // On vérifie si la date de début est dépassée 24h
            // Si oui, on passe le statut en "passée"
            // Normalement il faudrait faire un calcul par rapport à la durée de l'evenement,
            // A completer à l'occasion

            $currentDateTime = new \DateTime(); // Date et heure actuelles
            $next24HoursDateTime = clone $currentDateTime;
            $next24HoursDateTime->modify('+24 hours'); // Ajoute 24 heures à la date actuelle

            if ($sortie->getDateHeureDebut() <= $next24HoursDateTime) {
                if ($sortie->getEtat()->getId() !== 5) {
                    $etatEnCours = $entityManager->getRepository(Etat::class)->find(5);
                    $sortie->setEtat($etatEnCours);

                    $this->addFlash('bg-success text-white', 'Activité terminée');

                    $entityManager->persist($sortie);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'participants' => $participantsInscrits
        ]);

    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $organisateur = $sortie->getOrganisateur();
        $utilisateurActuel = $this->security->getUser();

        if ($utilisateurActuel == $organisateur){
            $form = $this->createForm(SortieType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

               // vérifier si le bouton "publier" à été cliqué
                if ($request->request->get('publier') === 'Publier') {
                    $etatOuvert = $entityManager->getRepository(Etat::class)->find(2);
                    $sortie->setEtat($etatOuvert);
                    $this->addFlash('bg-success text-white', 'La sortie a bien été publiée.');
                }elseif ($request->request->get('annuler') === 'Annuler') {
                    $etatAnnule = $entityManager->getRepository(Etat::class)->find(6);
                    $sortie->setEtat($etatAnnule);
                    $this->addFlash('bg-success text-white', 'La sortie a bien été annulée.');
                }
                $entityManager->flush();
                $this->addFlash('bg-warning text-dark', 'La sortie a bien été modifée');

                return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);

            }

            return $this->render('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        }else{
            $this->addFlash('bg-danger text-white', 'Vous n\'êtes pas autorisé a modifier cette evenement');
            return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
        }


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

        // On vérifie si la date du jour a dépassé la date limite d'inscription
        if ($sortie->getDateLimiteInscription() <= now()) {
            $etatCloture = $entityManager->getRepository(Etat::class)->find(3);
            $sortie->setEtat($etatCloture);
            $this->addFlash('bg-success text-white', 'Date d\'inscription dépassée');
            return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
        } else {
            // Vérification de si l'utilisateur n'est pas déjà inscrit à la sortie
            if (!$sortie->getInscrit()->contains($participant)) {
                $sortie->addInscrit($participant);

                $sortie->setNbInscrits($sortie->getNbInscrits() + 1);

                if (($sortie->getNbInscrits()) === ($sortie->getNbInscriptionsMax())) {
                    $etatCloture = $entityManager->getRepository(Etat::class)->find(3);
                    $sortie->setEtat($etatCloture);
                    $this->addFlash('bg-success text-white', 'Les inscriptions sont completes.');
                }

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('bg-success text-white', 'Vous êtes inscrit à cette sortie !');

            } else {

                $this->addFlash('bg-danger text-white', 'Vous êtes déjà inscrit à cette sortie.');
            }
        }

        return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/desincription', name: 'app_sortie_desinscription', methods: ['GET', 'POST'])]
    public function desinscription(int $id, EntityManagerInterface $entityManager): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $participant = $this->getUser();

        if (!$sortie || !$participant) {
            // Verification de l'existence de la sortie ou du participant
            return new Response('Sortie ou participant non trouvé', 404);
        }

        // On vérifie si la date du jour a dépassé la date limite d'inscription
        if ($sortie->getDateLimiteInscription() <= now()) {
            $etatCloture = $entityManager->getRepository(Etat::class)->find(3);
            $sortie->setEtat($etatCloture);
            $this->addFlash('bg-success text-white', 'Date d\'inscription dépassée - Impossible de vous désinscrire');
            return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
        } else {
            // Vérification que l'utilisateur soit bien inscrit à la sortie
            if ($sortie->getInscrit()->contains($participant)) {
                $sortie->removeInscrit($participant);

                $sortie->setNbInscrits($sortie->getNbInscrits() - 1);

                if (($sortie->getNbInscrits()) < ($sortie->getNbInscriptionsMax())) {
                    $etatOuvert = $entityManager->getRepository(Etat::class)->find(2);
                    $sortie->setEtat($etatOuvert);
                    $this->addFlash('bg-success text-white', 'Les inscriptions sont à nouveau ouvertes.');
                }

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('bg-success text-white', 'Vous n\'êtes plus inscrit à cette sortie !');

            } else {

                $this->addFlash('bg-danger text-white', 'Vous n\'êtes pas inscrit à cette sortie.');
            }
        }


        return $this->redirectToRoute('sortir_main', [], Response::HTTP_SEE_OTHER);
    }



}
