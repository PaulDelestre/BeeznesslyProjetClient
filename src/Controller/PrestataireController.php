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
 * @Route("/prestataire", name="prestataire_")
 * @IsGranted("ROLE_EXPERT")
 */
class PrestataireController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('prestataire/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/mon-compte", name="en_attente")
     */
    public function moderation(): Response
    {
        return $this->render('prestataire/validation.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

     /**
     * @Route("/messagerie", methods={"GET"}, name="messagerie")
     */
    public function message(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('prestataire/messagerie.html.twig', [
            'contacts' => $user->getContacts()
        ]);
    }

    /**
     * @Route("/messagerie/{id}", name="messagerie_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('prestataire/show.html.twig', [
            'contact' => $contact,
        ]);
    }

      /**
     * @Route("/ebook", methods={"GET"}, name="ebook")
     */
    public function ebooks(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('prestataire/ebook.html.twig', [
            'ebooks' => $user->getEbooks()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('prestataire_index');
        }

        return $this->render('prestataire/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
