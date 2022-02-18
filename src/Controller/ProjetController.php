<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Entity\Competence;
use App\Repository\ProjetRepository;
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
     * @Route("/new", name="projet_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {   

        $id=1;
        $user = $this->security->getUser();
        $skills=$this->getDoctrine()->getRepository(Competence::class)->findAll();
        
        //$competence=$this->getDoctrine()->getRepository(Competence::class)->find(1);
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            
            foreach($request->request->get('projet') ['competences'] as $idc){
             $comp=$this->getDoctrine()->getRepository(Competence::class)->find($idc);
             $projet->addCompetence($comp);
            }
            $projet->setStatut('pending');
            $projet->setUser($user);
            //$competence->addProjet($projet);
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            'skills' =>$skills,
        ]);
    }


}
