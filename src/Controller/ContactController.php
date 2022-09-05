<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', "Votre demande a bien été prise en compte. Nous reviendrons vers vous dès que nous l'aurons traitée.");
            
            $subject = $form->get('subject')->getData();
            $name = $form->get('firstname')->getData().' '.$form->get('lastname')->getData();
            $email = $form->get('email')->getData();
            $content = $form->get('content')->getData();

            $adminContent = "Détail de la demande :<br/><br/>
            Nom :<br/>".$name."<br/><br/>
            Adresse mail :<br/>".$email."<br/><br/>
            Message :<br/>".$content;
            $mail = new Mail();
            $mail->send('unicefbressuire@gmail.com', 'Unicef Bressuire', $subject.' de '.$name, $adminContent);
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
