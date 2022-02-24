<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContratController extends AbstractController
{
    /**
     * @Route("/contrat", name="contrat_index", methods={"GET"})
     */
    public function index(ContratRepository $contratRepository): Response
    {
        //return $this->render('contrat/index.html.twig', [
          //  'contrats' => $contratRepository->findAll(),
       // ]);

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
        if ($form->isSubmitted()) {
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



    /**
     * @Route("back/contrat", name="contrat_indexy", methods={"GET"})
     */
    public function indexy(ContratRepository $contratRepository): Response
    {
        //return $this->render('contrat/index.html.twig', [
        //  'contrats' => $contratRepository->findAll(),
        // ]);

        return $this->render('back/contrat/show.html.twig', [
            'contrats' => $contratRepository->getAllData(),
        ]);
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






















}
