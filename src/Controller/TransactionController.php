<?php

namespace App\Controller;

use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TransactionController extends AbstractController
{
    private $RepoTrans;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    /**
     * @Route("/transactions", name="transactions")
     */
    public function transactions(): Response
    {
        $user = $this->security->getUser();
        $trasactions=$this->getDoctrine()->getRepository(Transaction::class)->findBy(['user'=> $user]);
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
            'transactions'=>$trasactions
        ]);
    }
}
