<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public function __construct(Security $security)
    {
       $this->security = $security;
    }

   
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->security->getUser(); // null or UserInterface, if logged in
        // ... do whatever you want with $user
       
        return $this->render('user/index.html.twig', [
            'user' => $user , 
    
        ]);
    }


    /**
     * @Route("/pdf", name="immprimer", methods={"GET"})
     */
    public function Formationpdf( UserRepository $REC)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user/index.html.twig', [
            'user'=> $REC->findAll()
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/editProfil", name="editProfil")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $user = $this->security->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
      
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $file = $user->getPhoto();
            if($file){
                $uploads_directory = $this->getParameter('upload_directory');
                $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
                $file->move(
                    $uploads_directory,
                    $fileName
                );
                $user->setPhoto($fileName);
            }
            else
            {
                $user->setPhoto($user->getPhoto());
            }
               
                
           
            foreach($request->request->get('user') ['competences'] as $idc){
                $comp=$this->getDoctrine()->getRepository(Competence::class)->find($idc);
                $user->addCompetence($comp);
            }
            
            $entityManager->flush();
            $message = (new \Swift_Message('New'))

                ->setFrom('lancitounsi@gmail.com')

                ->setTo($user->getEmail())

                ->setSubject('modification terminé')


                ->setBody(
                    $this->renderView(
                        'user/email.html.twig'),

                    'text/html'
                );


            $mailer->send($message);
            return $this->redirectToRoute('home');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}

