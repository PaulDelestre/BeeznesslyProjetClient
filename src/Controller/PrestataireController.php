<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Ebook;
use App\Form\UserType;
use App\Form\EbookType;
use App\Form\ExpertType;

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
     * @Route("/profil", name="profil")
     */
    public function profil(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('prestataire/profil.html.twig', [
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
            'contacts' => $user->getContacts(),
            'user' => $user = $this->getUser()
        ]);
    }

    /**
     * @Route("/messagerie/{id}", name="messagerie_show", methods={"GET"})
     */
    public function showMessage(Contact $contact): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('prestataire/show_message.html.twig', [
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
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('prestataire/ebook.html.twig', [
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

            return $this->redirectToRoute('prestataire_profil');
        }

        return $this->render('prestataire/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ebook/new", name="ebook_new", methods={"GET","POST"})
     */
    public function newEbook(Request $request): Response
    {
        $ebook = new Ebook();
        $form = $this->createForm(EbookType::class, $ebook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ebook->setUser($this->getUser());
            $ebook->setIsValidated(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ebook);
            $entityManager->flush();

            return $this->redirectToRoute('prestataire_ebook');
        }

        return $this->render('prestataire/ebook_new.html.twig', [
            'ebook' => $ebook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ebook/{id}", name="ebook_show", methods={"GET"})
     */
    public function showEbook(Ebook $ebook): Response
    {
        return $this->render('prestataire/ebook_show.html.twig', [
            'ebook' => $ebook,
        ]);
    }

    /**
     * @Route("/ebook/{id}/edit", name="ebook_edit", methods={"GET","POST"})
     */
    public function editEbook(Request $request, Ebook $ebook): Response
    {
        $form = $this->createForm(EbookType::class, $ebook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prestataire_ebook');
        }

        return $this->render('prestataire/ebook_edit.html.twig', [
            'ebook' => $ebook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ebook/{id}", name="ebook_delete", methods={"DELETE"})
     */
    public function deleteEbook(Request $request, Ebook $ebook): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ebook->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ebook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prestataire_ebook');
    }

    /**
     * @Route("/page-expert", name="expertPage")
     */
    public function expertPage(): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('prestataire/expertPage.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/page-expert/edit/{id}", name="expertPage_edit", methods={"GET","POST"})
     */
    public function editExpertPage(Request $request, User $user): Response
    {
        $user = $this->getUser();
        if ($user->getIsValidated() == false) {
            return $this->render('prestataire/validation.html.twig', [
                'user' => $user,
            ]);
        }

        $form = $this->createForm(ExpertType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('prestataire_expertPage');
        }

        return $this->render('prestataire/edit_expertPage.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
