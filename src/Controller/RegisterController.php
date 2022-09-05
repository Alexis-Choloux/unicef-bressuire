<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $searchEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if (!$searchEmail) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
    
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = 'Bonjour '.$user->getFirstname().",<br/><br/>
                Nous vous souhaitons la bienvenue sur notre site.<br/><br/>
                Vous pouvez désormais vous rendre sur votre compte pour modifier et/ou supprimer vos paramètres.<br/><br/>
                Vous pouvez également passer commande sur notre boutique en ligne pour contribuer à la protection des enfants dans le monde.<br/><br/>
                A bientôt sur UnicefBressuire.fr !";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Inscription Unicef Bressuire', $content);

                $notification = 'Vous êtes maintenant inscrit à Unicef Bressuire !';
            } else {
                $notification = 'L\'email que vous avez renseigné correspond déjà à un compte existant.';
            }

        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }   
}
