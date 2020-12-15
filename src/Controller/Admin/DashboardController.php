<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ebook;
use App\Entity\Expertise;
use App\Entity\Provider;
use App\Entity\Service;
use App\Entity\TypeService;
use App\Entity\User;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('0920 Paris Php Beeznessly');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Modération', 'fas fa-tools')->setSubItems([
            MenuItem::linkToCrud('Experts', 'fas fa-user-cog', User::class)
            ->setController(ModerationExpertController::class),
            MenuItem::linkToCrud('Ebooks', 'fas fa-user-cog', Ebook::class)
            ->setController(ModerationEbookController::class),
        ]);
        yield MenuItem::linkToCrud('Ebook', 'fas fa-list', Ebook::class)
            ->setController(EbookCrudController::class);
        yield MenuItem::linkToCrud('Expertise', 'fas fa-list', Expertise::class);
        yield MenuItem::linkToCrud('Provider', 'fas fa-list', Provider::class);
        yield MenuItem::linkToCrud('Service', 'fas fa-list', Service::class);
        yield MenuItem::linkToCrud('Type Service', 'fas fa-list', TypeService::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class)
            ->setController(UserCrudController::class);
    }
}
