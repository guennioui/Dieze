<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Transporteur;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DiezeMarket');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('produit', 'fas fa-list', Produit::class);
        yield MenuItem::linkToCrud('categorie', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('transporteur', 'fas fa-list', Transporteur::class);
    }
}
