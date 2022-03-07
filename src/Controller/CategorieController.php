<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Contrat;
use App\Entity\User;
use App\Form\CategorieType;
use App\Form\ContratType;
use App\Repository\CategorieRepository;
//use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("back/categorie")
 */
class CategorieController extends AbstractController
{
    //public function __construct(Security $security)
    //{
     //   $this->security = $security;
  //  }
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
       // return $this->render('categorie/index.html.twig', [
         //   'categories' => $categorieRepository->findAll(),
      //  ]);

        return $this->render('back/categorie/show.html.twig', [
            'categories' => $categorieRepository->getAllData(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,\Swift_Mailer $mailer): Response
    {
        $categorie = new Categorie();
        //$user = $this->security->getUser();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            $message = (new \Swift_Message('New'))

                ->setFrom('lancitounsi@gmail.com')

                ->setTo('samyassine007@gmail.com')

                ->setSubject('CATEGORIE bien ajoutée')


                ->setBody(
                    $this->renderView(
                        'Back/categorie/email.html.twig'),

                    'text/html'
                );


            $mailer->send($message);

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/categorie/add.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
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
     * @Route("/back/{id}/edit", name="categorie")
     */

    public function updateClassroom(Request $request,$id)
    {
        $Categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class, $Categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('categorie_index');
        }
        return $this->render("back/categorie/edit.html.twig",array('form'=>$form->createView()));
    }












    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("sta", name="sta")
     */
    public function indexAction(CategorieRepository $T)
    {
        $cat = $T->findAll();

        $Développeur = 0;
        $datascience = 0;
        $marketing = 0;


        foreach ($cat as $counter) {
            if ($counter->getNom() == "Développeur")  :

                $Développeur += 1;
            elseif ($counter->getNom() == "data science"):

                $datascience += 1;
            elseif ($counter->getNom() == "marketing"):

            $marketing += 1;

            endif;

        }
        return $this->render('Back/categorie/sta.html.twig', [
            'Développeur' => $Développeur,
            'datascience' =>$datascience,
            'marketing' =>$marketing,


        ]);


    }


}
