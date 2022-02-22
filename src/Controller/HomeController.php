<?php

namespace App\Controller;
use App\Entity\Projet;
use App\Entity\User;
use App\Entity\Reclamation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'projet' => $projet,
            'user' => $user,
            'reclamation' => 'ReclamationController',
        ]);

    }
}
