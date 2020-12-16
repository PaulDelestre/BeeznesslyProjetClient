<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Entity\Contact;
use App\Form\UserType;

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
     * @Route("/messagerie", methods={"GET"}, name="prestataire_messagerie")
     */
    public function message(): Response
    {

            return $this->render('prestataire/messagerie.html.twig', [
            'contacts' => $this->getUser()->getContacts()
            ]);
    }

      /**
     * @Route("/ebook", methods={"GET"}, name="prestataire_ebook")
     */
    public function ebooks(): Response
    {

            return $this->render('prestataire/ebook.html.twig', [
            'ebooks' => $this->getUser()->getEbooks()
            ]);
    }

    /**
     * @IsGranted("ROLE_EXPERT")
     * @Route("/edit/{id}", name="prestataire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prestataire');
        }

        return $this->render('prestataire/edit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
}
