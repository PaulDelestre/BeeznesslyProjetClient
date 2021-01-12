<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
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
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Messages', 'fas fa-envelope', Contact::class);
        yield MenuItem::subMenu('Modération', 'fas fa-check-circle')->setSubItems([
            MenuItem::linkToCrud('Experts', 'fas fa-user-cog', User::class)
            ->setController(ModerationExpertController::class),
            MenuItem::linkToCrud('Ebooks', 'fas fa-book', Ebook::class)
            ->setController(ModerationEbookController::class),
        ]);
        yield MenuItem::subMenu('Infos', 'fas fa-info')->setSubItems([
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)
            ->setController(UserCrudController::class),
            MenuItem::linkToCrud('Ebook', 'fas fa-book', Ebook::class)
            ->setController(EbookCrudController::class),
        ]);
        yield MenuItem::subMenu('Gestion', 'fas fa-pencil-ruler')->setSubItems([
            MenuItem::linkToCrud('Expertises', 'fas fa-briefcase', Expertise::class),
            MenuItem::linkToCrud("Type de prestataire", 'fas fa-city', Provider::class),
            MenuItem::linkToCrud('Type de Service', 'fas fa-concierge-bell', TypeService::class)
        ]);
    }
}
