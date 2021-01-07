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
 * @Route("/entrepreneur", name="entrepreneur_")
 * @IsGranted("ROLE_ENTREPRENEUR")
 */
class EntrepreneurController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('entrepreneur/index.html.twig', [
            'user' => $user,
        ]);
    }

      /**
     * @Route("/profil", name="profil")
     */
    public function profil(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('entrepreneur/profil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/mon-compte", name="en_attente")
     */
    public function moderation(): Response
    {
        return $this->render('entrepreneur/validation.html.twig', [
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
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('entrepreneur/messagerie.html.twig', [
            'contacts' => $user->getContacts(),
            'user' => $user = $this->getUser()
        ]);
    }

    /**
     * @Route("/messagerie/{id}", name="messagerie_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('entrepreneur/show_message.html.twig', [
            'contact' => $contact,
            'user' => $user = $this->getUser()
        ]);
    }

      /**
     * @Route("/ebook", methods={"GET"}, name="ebook")
     */
    public function ebooks(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('entrepreneur/ebook.html.twig', [
            'ebooks' => $user->getEbooks(),
            'user' => $user = $this->getUser()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('entrepreneur/validation.html.twig', [
                'user' => $user,
            ]);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entrepreneur_profil');
        }

        return $this->render('entrepreneur/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
