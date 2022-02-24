<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Contrat;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    /**
     * @Route("/{id}", name="facture_index", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository , $id): Response
    {
        return $this->render('facture/index.html.twig', [
            'facture' => $factureRepository->getAllData($id),
        ]);
    }


    /**
     * @Route("/new", name="facture_new", methods={"GET", "POST"})
     */
/*
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

*/
    /**
     * @Route("/{id}", name="facture_show", methods={"GET"})
     */
    /*
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture
        ]);
    }
*/


    /**
     * @Route("/affichage", name="facture_affichage")
     */

    /*
    public function listStudenstudentsPerDateofBirtht(FactureRepository  $repository)
    {
        $facture = $repository->findAll();
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

*/








    /*
        /**
         * @Route("/{id}/edit", name="facture_edit", methods={"GET", "POST"})
         */
    /*
   public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
   {
       $form = $this->createForm(FactureType::class, $facture);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $entityManager->flush();

           return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
       }

       return $this->render('facture/edit.html.twig', [
           'facture' => $facture,
           'form' => $form->createView(),
       ]);
   }
*/

    /**
     * @Route("/add", name="add")
     */
    public function addStudent(Request $request)
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            //$facture->setMoyenne(0);
            $em->persist($facture);
            $em->flush();
            return $this->redirectToRoute('facture_show');
        }
        return $this->render("facture/new.html.twig", array('form' => $form->createView()));
    }














   /**
    * @Route("/{id}", name="facture_delete", methods={"POST"})
    */
/*
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_affichage', [], Response::HTTP_SEE_OTHER);
    }
*/
}



