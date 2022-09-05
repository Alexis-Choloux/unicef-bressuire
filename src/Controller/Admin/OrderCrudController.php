<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Prête', 'fas fa-box')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livrée', 'fas fa-box-open')->linkToCrudAction('updateDelivery');
        $updateCancel = Action::new('updateCancel', 'Annulée', 'fas fa-times-circle')->linkToCrudAction('updateCancel');
        return $actions
            ->add('index', 'detail')
            ->disable('delete')
            ->add('detail', $updateDelivery)
            ->add('detail', $updatePreparation)
            ->add('detail', $updateCancel);
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(1);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:Coral'><strong>La commande</strong> "
            . $order->getOrderId()
            . " <strong>est prête !</strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        $mail = new Mail();
        $content = 'Bonjour ' . $order->getUser()->getFirstname() . ",<br/><br/>
            Votre commande est prête !<br/><br/>
            Merci de vous rendre au lieu suivant :<br/><br/>" . $order->getCacAddress();
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Commande prête - Unicef Bressuire', $content);

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:SeaGreen'><strong>La commande</strong> "
            . $order->getOrderId()
            . " <strong>est livrée !</strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        $mail = new Mail();
        $content = 'Bonjour ' . $order->getUser()->getFirstname() . ",<br/><br/>
            Votre commande a bien été collectée par vous, ou l'un de vos proches !<br/><br/>
            Merci encore pour votre achat responsable, et à bientôt sur UnicefBressuire.fr !";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Commande réceptionnée - Unicef Bressuire', $content);

        return $this->redirect($url);
    }

    public function updateCancel(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:DarkGray'><strong>La commande</strong> "
            . $order->getOrderId()
            . " <strong>a été annulée !</strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        $mail = new Mail();
        $content = 'Bonjour ' . $order->getUser()->getFirstname() . ",<br/><br/>
            Votre commande a bien été annulée.<br/><br/>
            Bonne journée, et à bientôt sur Unicef Bressuire !";

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('state', 'Etat')->setChoices([
                'Non-livrée' => 0,
                'Prête' => 1,
                'Livrée' => 2,
                'Annulée' => 3
            ]),
            DateField::new('createdAt', 'Date'),
            TextField::new('user.getFullName', 'Utilisateur'),
            TextField::new('contact', 'Contact')->onlyOnDetail(),
            TextField::new('contact_preference', 'Préférence de contact')->onlyOnDetail(),
            TextField::new('availabilities', 'Disponibilités')->onlyOnDetail(),
            TextEditorField::new('cacAddress', 'Adresse Click&Collect')->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR')->hideOnForm(),
            TextField::new('order_id', 'ID'),
            ArrayField::new('orderDetails', 'Produits commandés')->hideOnIndex()->hideOnForm()
        ];
    }
}
