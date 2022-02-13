<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/connexion", name="login")
     */
    public function login(){
        return $this->render('security/login.html.twig');

    }
    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}
     
}
