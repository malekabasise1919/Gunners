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
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'projet' => $projet,
            'user' => $user,
            'reclamation' => 'ReclamationController',
        ]);

    }
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/map", name="map")
     */
    public function map(): Response
    {
        return $this->render('newMap.html.twig');
    }
    /**
     * @Route("/street", name="streetview")
     */
    public function street(): Response
    {
        return $this->render('streetview.html.twig');
    }

}
