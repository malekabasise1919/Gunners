<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("back/competence")
 */
class CompetenceController extends AbstractController
{
    /**
     * @Route("/", name="competence_index", methods={"GET"})
     */
    public function index(CompetenceRepository $competenceRepository): Response
    {
        // return $this->render('competence/index.html.twig', [
        //   'competence' => $competenceRepository->findAll(),
        //  ]);

        return $this->render('back/competence/show.html.twig', [
            'competence' => $competenceRepository->getAllData(),
        ]);
    }

    /**
     * @Route("/new", name="competence_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/competence/add.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competence_show", methods={"GET"})
     */
    public function show(Competence $competence): Response
    {
        return $this->render('competence/show.html.twig', [
            'competence' => $competence,
        ]);
    }
    /*
        /**
         * @Route("/{id}/edit", name="categorie_edit", methods={"GET", "POST"})
         */
    /*
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    */


    /**
     * @Route("/back/{id}/edit", name="competence")
     */

    public function updateClassroom(Request $request,$id)
    {
        $Competence = $this->getDoctrine()->getRepository(Competence::class)->find($id);
        $form = $this->createForm(CompetenceType::class, $Competence);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('competence_index');
        }
        return $this->render("back/competence/edit.html.twig",array('form'=>$form->createView()));
    }













    /**
     * @Route("/{id}", name="competence_delete", methods={"POST"})
     */
    public function delete(Request $request, Competence $competence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($competence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('competence_index', [], Response::HTTP_SEE_OTHER);
    }
}
