<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getAll(),
        ]);
    }

    /**
     * @Route("/commande/validation", name="order_validation", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $cacAddress = $form->get('cacaddresses')->getData();
            $contact = $form->get('addresses')->getData();
            $contactContent = $contact->getPhone().', '.$contact->getAddress().', '.$contact->getPostal().' '.$contact->getCity();
            $orderId = Uuid::v1();
            $contactPref = $form->get('contact_preference')->getData();
            $availabilities = $form->get('availabilities')->getData();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCacAddress($cacAddress);
            $order->setContact($contactContent);
            $order->setState(0);
            $order->setOrderId($orderId);
            $order->setContactPreference($contactPref);
            $order->setAvailabilities($availabilities);

            $this->entityManager->persist($order);


            foreach ($cart->getAll() as $article) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($article['product']->getName());
                $orderDetails->setQuantity($article['quantity']);
                $orderDetails->setPrice($article['product']->getPrice());
                $orderDetails->setTotal($article['product']->getPrice() * $article['quantity']);

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            $cart->remove();

            $mail = new Mail();
            $content = 'Bonjour '.$order->getUser()->getFirstname().",<br/><br/>
            Votre commande a bien été validée.<br/><br/>
            Nous allons bientôt prendre contact avec vous par ".$contactPref.'<br/><br/>
            Merci pour votre achat responsable et pour votre contribtion à UNICEF !';
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Commande validée - Unicef Bressuire', $content);

            $adminContent = "Une commande vient d'être passée sur le site Unicef Bressuire.<br/><br/>
            Détails :<br/><br/>
            Nom : ".$order->getUser()->getFirstname().' '.$order->getUser()->getLastname()."<br/>
            Livraison demandée :<br/>".$cacAddress."<br/>
            Précisions :<br/>".$availabilities."<br/>
            Contact demandé :<br/>".$contactPref."
            Contact :<br>".$contact;
            $mail->send('unicefbressuire@gmail.com', 'Admin Unicef Bressuire', 'Nouvelle commande - '.$orderId, $adminContent);

            return $this->render('order/add.html.twig', [
                'order' => $order,
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
