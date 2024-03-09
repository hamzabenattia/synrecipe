<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, EmailService $mailer): Response
    {
        $contact = new Contact();
        $user = $this->getUser();
        $user &&  $contact->setFullName($user->getFullName())->setEmail($user->getEmail());
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($contact);
            $manager->flush();

            
            $mailer->sendEmail(
                $contact->getEmail(),
                'admin@kh5zlaq8.mailosaur.net',
                $contact->getSubject(),
                'hello',
                '<h1>hello</h1>'
            );
            


            $this->addFlash('success', 'Email envoyé avec succès !');
            return $this->redirectToRoute('app_contact');
        }



        return $this->render('pages/contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
          
        ]);
    }
}
