<?php

namespace App\Controller;

use App\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture/{id}", name="facture")
     */
    public function index($id): Response
    {
        $Facture =$this->getDoctrine()->getRepository(Facture::class)->find($id);
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
            'facture'=>$Facture
        ]);
    }
}
