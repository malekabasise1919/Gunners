<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Form\PaymentType;
use App\Repository\TransactionRepository;
use phpDocumentor\Reflection\Types\Integer;

class PaymentController extends AbstractController
{
    private $RepoTrans;

    public function __construct(Security $security , TransactionRepository $r)
    {
       $this->security = $security;
       $this->RepoTrans=$r;
    }

    /**
     * @Route("/payment", name="offre")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

     /**
     * @Route("/payment/offre/{prix}", name="payment_offre")
     */
    public function payment($prix , Request $request , EntityManagerInterface $entityManager ): Response
    {
        $d = new \Datetime('now');
        $dt=$d->format('Y-m');
        $user = $this->security->getUser();
        $trasactions=$this->RepoTrans->LastFiveTrans($user);
    
        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trans = new Transaction();
            $facture= new Facture();
            $facture->setCreatedAt();
            
            $trans->setCreatedAt();
            $trans->setUser($user);
            $trans->setStatut('Payment Par Card');
            $bids= $user->getBids();
            if($bids==null){
                $bids=0;
            }
            if($prix=='30'){
              $user->setBids($bids+100);
              $trans->setType('BASIC PLANS');
              $trans->setPrix(30);
              
            }
            if($prix=='60'){
                $user->setBids($bids+200);
                $trans->setType('STANDARD PLANS');
                $trans->setPrix(60);
            }
            if($prix=='100'){
                $user->setBids($bids+350);
                $trans->setType('PROFESSNIONAL PLANS');
                $trans->setPrix(100);
            }
            $entityManager->persist($trans);
            $entityManager->flush();
            $facture->setId($user->getId().$trans->getId().intval(date('m')).intval($dt));
            $trans->setFacture($facture);
            $entityManager->persist($facture);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Bids are added To your account !'
            );
            return $this->redirectToRoute('home');
           
        }
        
        
        return $this->render('payment/payment_offre.html.twig', [
            'form' => $form->createView(),
            'date' =>$dt,
            'transactions'=>$trasactions
            

        ]);
    }
}
