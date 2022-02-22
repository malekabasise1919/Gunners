<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public function __construct(Security $security)
    {
       $this->security = $security;
    }

   
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->security->getUser(); // null or UserInterface, if logged in
        // ... do whatever you want with $user
        $nom=$user->getNom();
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user' =>$nom
        ]);
    }

   

  

    /**
     * @Route("/editProfil", name="editProfil")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->security->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
