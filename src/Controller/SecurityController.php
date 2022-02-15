<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{

    /**
     * @Route("/connexion", name="login")
     */
    public function login( AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig' , [
            'error'=>$error
        ]);

    }
    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout(){}

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration( Request $request ,  EntityManagerInterface $manager ,
    UserPasswordEncoderInterface $encoder){

        $user=new User();
        $form= $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setVerified(1);
            $user->setCreatedAt();
            $user->setBanned(0);
            $hash = $encoder->encodePassword($user , $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("login");
        }
 
        
        
        return $this->render('security/registration.html.twig',[
            
            'form'=>$form->createView()
        ]);
    }
     
}
