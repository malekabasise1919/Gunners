<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Projet;
use App\Entity\Proposition;
use App\Entity\User;
use App\Form\ContratType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BidsController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/bids", name="bids")
     */
    public function myProjects(): Response
    {

        $user = $this->security->getUser();
        $project = $this->getDoctrine()->getRepository(Projet::class)->findBy(['user' => $user]);

        return $this->render('bids/index.html.twig', [
            'user'=>$user,
            'projects' => $project,
        ]);
    }

    /**
     * @Route("/manageBids/{id}", name="managebids")
     */
    public function manageBids($id): Response
    {

        $project = $this->getDoctrine()->getRepository(Projet::class)->find($id);
        $proposition = $this->getDoctrine()->getRepository(Proposition::class)->findBy(['projet' =>  $project]);




        return $this->render('bids/manageBids.html.twig', [
            'propositions'=>$proposition,
            'projects' => $project,
        ]);
    }

    /**
     * @Route("/addContrat/{id}/{id_freelancer}/{prix}", name="addcontrat")
     */
    public function addContrat($id,Request $request, EntityManagerInterface $entityManager,$id_freelancer,$prix): Response
    {
        $user = $this->security->getUser();

        $contrat=new Contrat();
        $project = $this->getDoctrine()->getRepository(Projet::class)->find($id);
       $user_freelancer=$this->getDoctrine()->getRepository(User::class)->find($id_freelancer);
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

            $contrat->setUserClient($user);
            $contrat->setStatut('pending');
            $contrat->setPrix($prix);
            $contrat->setProjet($project);
            $contrat->setCreatedAt();
            $contrat->setUserFreelancer($user_freelancer);
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('bids', [], Response::HTTP_SEE_OTHER);




        return $this->render('bids/manageBids.html.twig', [

            'form' => $form->createView(),
        ]);
    }



}
