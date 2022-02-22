<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Entity\Projet;
use App\Entity\Proposition;
use App\Form\ProjetType;
use App\Entity\Competence;
use App\Form\PropositionType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Security\Core\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

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
     * @Route("/", name="projets")
     */
    public function index(): Response
    {
        $projects = $this->getDoctrine()->getRepository(Projet::class)->findBy([],['id' => 'DESC']);
        return $this->render('projet/index.html.twig', [
            'projects' => $projects,
        ]);
    }




    /**
     * @Route("/new", name="projet_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {   


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

    /**
     * @Route("/{id}", name="project")
     */
    public function project($id,Request $request,EntityManagerInterface $entityManager)
    {
        $user = $this->security->getUser();
        $project = $this->getDoctrine()->getRepository(Projet::class)->find($id);

        $verifProp = $this->getDoctrine()->getRepository(Proposition::class)->findOneBy(['user' => $user,
            'projet' =>  $project]);
        $proposition=new Proposition();
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);



        if ($form->isSubmitted() ) {
            $proposition->setUser($user);
            $proposition->setProjet($project);
            $proposition->setCreatedAt();
            $entityManager->persist($proposition);
            $entityManager->flush();

            return $this->redirectToRoute('project', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('projet/viewProject.html.twig', [
            'verifProp'=>$verifProp,
            'project' => $project,

            'form' => $form->createView(),
        ]);
    }


}
