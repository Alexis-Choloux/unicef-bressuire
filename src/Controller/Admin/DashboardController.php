<?php

namespace App\Controller\Admin;

use App\Entity\CacAddress;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;


class DashboardController extends AbstractDashboardController
{
    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('assets/css/admin.css');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Unicef Bressuire');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-basket', Order::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tags', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-clipboard-list', Category::class);
        yield MenuItem::linkToCrud('Adresses CAC', 'fas fa-people-carry', CacAddress::class);
    }
}
