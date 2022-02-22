<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Projet;
use Symfony\Component\Security\Core\Security;
use App\Form\ReclamationType;
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
    public function new(Request $request, EntityManagerInterface $entityManager, $projet_id): Response
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($projet_id);
        $reclamation = new Reclamation();
        $reclamation->setProjet($projet);
        $reclamation->setDateDeReclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

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
    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }





    /**
     * @Route("/{id}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
