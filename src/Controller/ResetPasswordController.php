<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);

            if ($user) {
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTime());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken()
                ]);
                $content = 'Bonjour '.$user->getFirstname().",<br/><br/>
                Vous avez demandé une réinitialisation de votre mot de passe sur UnicefBressuire.fr<br/><br/>
                Merci de cliquer sur le lien suivant pour continuer la procédure :<br/><br/>
                <a href='".$url."'>mettre à jour mon mot de passe</a>";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Réinitialisation de mot de passe', $content);

                $this->addFlash('notice', 'Votre demande a été enregistrée. Merci de vous rendre dans votre boite mail pour continuer la procédure de réinitialisation de mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');

            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modification-mot-de-passe/{token}", name="update_password")
     */
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder) {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$resetPassword) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de réinitialisation de mot de passe a expiré. Veuillez la renouveler.');
            return $this->redirectToRoute('reset_password');
        }
        
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();
            $password = $encoder->encodePassword($resetPassword->getUser(), $new_pwd);

            $resetPassword->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour !');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' =>$form->createView()
        ]);

    }
}
