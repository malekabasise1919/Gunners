<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Reclamation;
use App\Form\ProjetType;
use App\Form\StatutProjType;
//use App\Entity\Competence;
use App\Form\ReclamationType;
use App\Repository\ProjetRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/projet")
 */
class ProjetController extends AbstractController
{

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("/", name="projet_index", methods={"GET"})
     */
    public function index(ProjetRepository $projetRepository): Response
    {
        $user = $this->security->getUser();
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findBy(['user' =>  $user]);
        return $this->render('reclamation/jareb.html.twig', [
            'projets' => $projet ,
        ]);
    }


    /**
     * @Route("/new", name="projet_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {   

        $id=1;
        $user = $this->security->getUser();
        //$skills=$this->getDoctrine()->getRepository(Competence::class)->findAll();
        
        //$competence=$this->getDoctrine()->getRepository(Competence::class)->find(1);
        $projet = new Projet();
        $projet->setStatut('pending');
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            
          /*  foreach($request->request->get('projet') ['competences'] as $idc){
             $comp=$this->getDoctrine()->getRepository(Competence::class)->find($idc);
             $projet->addCompetence($comp);
            }*/
            $projet->setUser($user);
            //$competence->addProjet($projet);
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            //'skills' =>$skills,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="projet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projet/edit.html.twig', [
            'projets' => $projet,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/del/{id}", name="projet_delete")
     */
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/back", name="projet_index_back", methods={"GET"})
     */
    public function index_proj_back(ProjetRepository $projetRepository): Response
    {
        $user = $this->security->getUser();
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findBy(['user' =>  $user]);
        return $this->render('back/Projet/show.html.twig', [
            'projets' => $projet ,
        ]);
    }
    /**
     * @Route("/back/{id}/edit", name="projet_edit_back", methods={"GET", "POST"})
     */
    public function edit_proj_back(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutProjType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('projet_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/Projet/edit.html.twig', [
            'projets' => $projet,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/back/{id}", name="projet_delete_back")
     */
    public function delete_proj_back(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projet_index_back', [], Response::HTTP_SEE_OTHER);
    }
   /* /**
     * @Route("/search", name="search")
     *//*
    public function search_project()
    {
        $form = $this->createForm(ProjetType::class);
        return $this->render('base.html.twig', [
            'form'=>$form->createView()
        ]);
    }
*/
}
