<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Projet;
use App\Form\StatutBackType;
use Symfony\Component\Security\Core\Security;
use App\Form\ReclamationType;
//use App\Form\SearchType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
     public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $reclamation=array();
        $user = $this->security->getUser();
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findBy(['user'=>$user]);
        foreach ($projet as $p){
            array_push($reclamation,$p->getReclamation());
        }
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation ,
        ]);
    }

    /**
     * @Route("/new/{projet_id} ", name="reclamation_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, $projet_id, \Swift_Mailer $mailer): Response
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($projet_id);
        $reclamation = new Reclamation();
        $reclamation->setProjet($projet);
        $reclamation->setStatut('pending');
        $reclamation->setDateDeReclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $message = (new \Swift_Message('New'))

                ->setFrom('lancitounsi@gmail.com')

                ->setTo('lancitounsi@gmail.com')

                ->setSubject('Réclamation bien ajoutée')


                ->setBody(
                    $this->renderView(
                        'reclamation/email.html.twig'),

                    'text/html'
                );


            $mailer->send($message);

            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /*
    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     /*
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    */
    /**
     * @Route("/del/{id}", name="reclamation_delete")
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($reclamation);
            $entityManager->flush();

        return $this->redirectToRoute('reclamation_index');
    }
    /**
     * @Route("/back", name="reclamation_index_back", methods={"GET"})
     */
    public function index_back(ReclamationRepository $reclamationRepository): Response
    {
        //$user = $this->security->getUser();
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        return $this->render('back/Reclamation/show.html.twig', [
            'reclamations' => $reclamations ,
        ]);
    }
    /**
     * @Route("/back/{id}/edit", name="reclamation_edit_back", methods={"GET", "POST"})
     */
    public function edit_back(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutBackType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/Reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/back/{id}", name="reclamation_delete_back")
     */
    public function delete_back(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index_back', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/backindex", name="reclamationback", methods={"GET"})
     */
    public function indexback(ReclamationRepository $Repo): Response
    {

        return $this->render('back/back.html.twig');
    }
/*
    /**
     * @Route("/Search", name="Search")
     *//*
    public function Search(Request $request, ReclamationRepository $repository)
    {
        $reclamations = $repository->findAll();
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $description = $searchForm['description']->getData();
            $resultOfSearch = $repository->search($description);
            return $this->render('reclamation/index.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchStudent" => $searchForm->createView()));
        }
        return $this->render('reclamation/index.html.twig', array(
            "reclamations" => $reclamations,
            "searchReclamation" => $searchForm->createView()));
    }

*/
}
