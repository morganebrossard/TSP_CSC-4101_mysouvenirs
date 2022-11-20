<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Album;
use App\Entity\Souvenir;
use App\Entity\Member;
use App\Entity\Tableau;



class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(AlbumCrudController::class)->generateUrl());

    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mysouvenirs');
    }

    public function configureMenuItems(): iterable
    {
        #On configure un menu permettant de circuler entre les entités facilement dans le back office
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Les albums', 'fas fa-list', Album::class);
        yield MenuItem::linkToCrud('Les souvenirs', 'fas fa-list', Souvenir::class);
        yield MenuItem::linkToCrud('Les membres', 'fas fa-list', Member::class);
        yield MenuItem::linkToCrud('Les tableaux', 'fas fa-list', Tableau::class);
    }
}
