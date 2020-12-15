<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/prestataire")
 * @IsGranted("ROLE_EXPERT")
 */
class PrestataireController extends AbstractController
{
    /**
     * @Route("/", name="prestataire")
     */
    public function index(): Response
    {
        return $this->render('prestataire/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

     /**
     * @Route("/messagerie", name="prestataire_messagerie")
     */
    public function message(): Response
    {
        return $this->render('messagerie/index.html.twig', [
            'message' => $this->getContacts(),
        ]);
    }
}