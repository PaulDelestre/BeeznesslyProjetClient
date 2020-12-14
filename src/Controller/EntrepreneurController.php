<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/entrepreneur")
 * @IsGranted("ROLE_ENTREPRENEUR")
 */
class EntrepreneurController extends AbstractController
{
    /**
     * @Route("/", name="entrepreneur")
     */
    public function index(): Response
    {
        return $this->render('entrepreneur/index.html.twig', [
            'controller_name' => 'EntrepreneurController',
        ]);
    }
}
