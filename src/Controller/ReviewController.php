<?php

namespace App\Controller;


use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Swift_Mailer;

class ReviewController extends AbstractController
{

    private $mailer;
    public function __construct(Security $security,Swift_Mailer $mailer)
    {
        $this->security = $security;
        $this->mailer = $mailer;
    }



    /**
     * @Route("/review", name="review")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();
        $reviews=$this->getDoctrine()->getRepository(Review::class)->findBy(['user'=>$user]);

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/add_review", name="add_review")
     */
    public function addReview(Request $request, \Swift_Mailer $mailer): Response
    {
        $review = new Review();
        $user = $this->security->getUser();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($user);

        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('syrine.benslim@esprit.tn')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderView(
                // templates/hello/email.txt.twig
                'user/email.txt.twig',
                ['name' => 'syrine' ]
            )
        )
    ;
    $mailer->send($message);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('freelancers');
        }

        return $this->render('review/add.html.twig', ['form' => $form->createView()]);


    }


}

