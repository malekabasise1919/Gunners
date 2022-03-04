<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Repository\PorfolioRepository;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PortfolioController extends AbstractController
{



    /**
     * @Route("/ajout_portfolio",name="ajout_portfolio")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response

     * @return Response
     */
    public function ajout_client(Request $request ): Response
    {
        $portfolio= new Portfolio();
        $form= $this->createForm(PortfolioType::class,$portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            $extensionList = array("png","jpeg","jpg","bnp");
            if ($uploadedFile && in_array($uploadedFile->guessExtension(),$extensionList))
            {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user=$this->getUser();
                $portfolio->setUser($user);
                $portfolio->setImage($newFilename);
                $em=$this->getDoctrine()->getManager();
                $em->persist($portfolio);
                $em-> flush();
            }



            return $this->redirectToRoute('liste');

        }
        return $this->render('portfolio/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/liste", name="liste")
     */
    public function list(){
        $repository=$this->getDoctrine()->getRepository(Portfolio::class);
        $user=$this->getUser();
        $portfolio=$user->getPortfolio();
        return $this->render('portfolio/index.html.twig',array(
            'portfolio'=>$portfolio
        ));

    }

    /**
     * @Route("Delete/{id}/",name="delete_admin")
     */

    public function DeleteAdmin($id ,PortfolioRepository  $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $portfolio = $repository->find($id);
        $em->remove($portfolio);
        $em->flush();
        return $this->redirectToRoute('liste');
    }

    /**
     * @Route ("adherant/Update{id}",name="Update")
     */
    function Update_Adherant(PortfolioRepository $repository, $id,Request $request)
    {

        $portfolio = $repository->find($id);
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            if ($uploadedFile)
            {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $portfolio->setImage($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('liste');


        }
        return $this->render('portfolio/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/my_freelancer_profile", name="my_freelancer_profile")
     */
    public function profile()
    {

        return $this->render('profile/index.html.twig');
    }
}
