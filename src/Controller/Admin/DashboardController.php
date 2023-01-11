<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();
        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FCTourisme');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute("Retour au site","fa fa-home","app_etablissements");

        yield MenuItem::section("Utilisateurs");
        yield MenuItem::subMenu("Actions",'fas fa-bars')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter un utilisateur',

                    'fas fa-plus',User::class)->setAction(Crud::PAGE_NEW),

                MenuItem::linkToCrud('Lister les utilisateurs',

                    'fas fa-eye',User::class)->setAction(Crud::PAGE_INDEX)

            ]);

        yield MenuItem::section("Etablissement");
        yield MenuItem::subMenu("Actions",'fas fa-bars')
            ->setSubItems([
                MenuItem::linkToCrud('Ajouter un etablissement',

                    'fas fa-plus',Etablissement::class)->setAction(Crud::PAGE_NEW),

                MenuItem::linkToCrud('Lister les etablissements',

                    'fas fa-eye',Etablissement::class)->setAction(Crud::PAGE_INDEX)

            ]);
    }
}
