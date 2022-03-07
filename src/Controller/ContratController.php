<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Pagerfanta;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
//use App\Entity\Projet;
//use App\Entity\User;



class ContratController extends Controller
{
    /*public function __construct(Security $security)
    {
        $this->security = $security;
    }
    */
    /**
     * @Route("/contrat", name="contrat_index", methods={"GET"})
     */
    public function index(ContratRepository $contratRepository): Response
    {
        //return $this->render('contrat/index.html.twig', [
        //  'contrats' => $contratRepository->findAll(),
        // ]);
       // $contrat=array();
        //$user = $this->security->getUser();
        //$projet = $this->getDoctrine()->getRepository(Contrat::class)->findBy(['user'=>$user]);
        //foreach ($projet as $p){
          //  array_push($contrat,$p->getContrat());
        //}
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->getAllData(),
        ]);
    }




    /**
     * @Route("/new", name="contrat_new", methods={"GET", "POST"})
     */
    /*
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->redirectToRoute('contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form->createView(),
        ]);
    }
*/


    /**
     * @Route("/{id}", name="contrat_show", methods={"GET"})
     */
    /*
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

*/
    /**
     * @Route("/back/{id}/edit", name="contratm")
     */

    public function updateClassroom(Request $request,$id)
    {
        $contrat = $this->getDoctrine()->getRepository(Contrat::class)->find($id);
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('contrat_indexy');
        }
        return $this->render("back/contrat/edit.html.twig",array('form'=>$form->createView()));
    }




    /*


        public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ContratType::class, $contrat);
            $form->handleRequest($request);
            $form->add("Modifier",SubmitType::class);


            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('contrat_indexy', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('back/contrat/edit.html.twig', [
                'contrat' => $contrat,
                'form' => $form->createView(),
            ]);
        }
    */
    /**
     * @Route("/contrat/supp/{id}", name="contrat_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrat_index', [], Response::HTTP_SEE_OTHER);

    }

    /*
        /**
         * @Route("back/contrat/{page<\d+>}",name="contrat_indexy", methods={"GET"})
         */
    /*
    public function indexy(ContratRepository $contratRepository, $qb, int $page=1): Response
    {
        //return $this->render('contrat/index.html.twig', [
        //  'contrats' => $contratRepository->findAll(),
        $qb = $contratRepository->createQueryBuilder('contrat')->addSelect('contrat');
        $pagerfanta = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        return $this->render('back/contrat/show.html.twig', [
            'contrats' => $pagerfanta,
        ]);
    }
*/


    /**
     * @Route("back/contrat", name="contrat_indexy", methods={"GET"})
     */
    public function indexy(ContratRepository $contratRepository, Request $request): Response
    {
        //return $this->render('back/contrat/show.html.twig', [
        //  'contrats' => $contratRepository->findAll(),
        // ]);
        $allcontrats = $contratRepository->findAll();

        if ($allcontrats) {
            foreach($allcontrats as $reservation)
            {
                $res[] = [
                  //  'id'=> $reservation->getId(),
                    'start'=> $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
                    'end'=> $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
                    // 'title'=> $reservation->getIdReservable()->getLoisir()->getTitle(),
                    'title'=> $reservation->getStatut(),
                ];
            }

            $data = json_encode($res);
        $contrats =$this->get('knp_paginator') ->paginate(
        // Doctrine Query, not results
            $allcontrats,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );


        return $this->render('back/contrat/show.html.twig', [
            'contrats' => $contrats,
            'data' => $data,

        ]);


    }

    }



    /**
     * @Route("/back/contrat/supp/{id}", name="contrat_deletey", methods={"GET", "POST"})
     */
    public function deletey(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contrat_indexy', [], Response::HTTP_SEE_OTHER);

    }





    /**
     * @Route("back/contrat/pdf", name="immprimer", methods={"GET"})
     */
    public function Formationpdf( FactureRepository $fRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('formationcl/Formationpdf.html.twig',
            ['facture' => $fRepository->findAll(),
            ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("formation.pdf", [
            "Attachment" => false
        ]);
    }




    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Contrat $contrat, Request $request,EntityManagerInterface $entityManager): Response
    { // On récupère les données
        $donnees = json_decode($request->getContent());
        if
        (isset($donnees->statut) && !empty($donnees->statut) && isset($donnees->created_at) && !empty($donnees->created_at) &&  isset($donnees->created_at) && !empty($donnees->created_at) )

        {  //les donnees sont completes
            $code=200;
            if(!$contrat)
            {

                $contrat=new Contrat();
                $code=201;

            }
            $contrat->setTimeStart(new DateTime($donnees->created_at));
            $contrat->setStatut($donnees->statut);

            if($donnees->allDay){
                $contrat->setTimeEnd(new DateTime($donnees->created_at));
            }
            else {
                $contrat->setTimeEnd(new DateTime($donnees->created_at));
            }
            $entityManager->persist($contrat);
            $entityManager->flush();
            return new Response('OK', $code);
        }




        else {
            // Les données sont incomplètes
            return new Response('données incomplete', 404);
        }


        return $this->render('Back/contrat/show.html.twig', [
            'controller_name' =>'Userreservation_show',
        ]);



    }



}
