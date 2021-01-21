<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ebook;
use App\Entity\Contact;
use App\Entity\Download;
use App\Form\RgpdFormType;
use App\Form\ContactFormType;
use App\Entity\User;
use App\Data\SearchEbooksData;
use App\Form\SearchEbooksType;
use App\Service\MailerService;
use App\Data\SearchExpertsData;
use App\Form\SearchExpertsType;
use App\Repository\UserRepository;
use App\Repository\EbookRepository;
use App\Repository\ExpertiseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ExpertiseRepository $expertiseRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'expertises' => $expertiseRepository->findAll()
        ]);
    }

    /**
     * @Route("/experts", name="home_experts")
     */
    public function allExperts(
        PaginatorInterface $paginator,
        UserRepository $userRepository,
        Request $request
    ): Response {

        $search = new SearchExpertsData();
        $searchForm = $this->createForm(SearchExpertsType::class, $search);
        $searchForm->handleRequest($request);
        $experts = $userRepository->searchExperts($search);

        $donnees = $this->getDoctrine()->getRepository(User::class)->findBy([], ['id' => 'desc']);


        // Paginate the results of the query
        $experts = $paginator->paginate(
            // Doctrine Query, not results
            $donnees,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            12
        );

        return $this->render('home/experts.html.twig', [
            'experts' => $experts,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/ebooks", name="home_ebooks")
     */
    public function allEbooks(
        PaginatorInterface $paginator,
        EbookRepository $ebookRepository,
        Request $request
    ): Response {

        $search = new SearchEbooksData();
        $searchForm = $this->createForm(SearchEbooksType::class, $search);
        $searchForm->handleRequest($request);
        $ebooks = $ebookRepository->searchEbooks($search);

        $donnees = $this->getDoctrine()->getRepository(Ebook::class)->findBy([], ['id' => 'desc']);


        // Paginate the results of the query
        $ebooks = $paginator->paginate(
            // Doctrine Query, not results
            $donnees,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            12
        );

        return $this->render('home/ebooks.html.twig', [
            'ebooks' => $ebooks,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/experts/{slug}", methods={"GET", "POST"}, name="home_expert_show")
     */
    public function showExpert(
        User $user,
        Request $request,
        MailerService $mailerService
    ): Response {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            $mailerService->sendEmailAfterContactExpert($contact);
            return $this->render('home/confirmation_message.html.twig');
        }

        return $this->render('home/expert_show.html.twig', [
            'user' => $user,
            'contact' => $contact,
            'contactForm' => $contactForm->createView()
        ]);
    }

    /**
     * @Route("/ebooks/{slug}", methods={"GET", "POST"}, name="home_ebook_show")
     */
    public function showEbook(Ebook $ebook, Request $request, UserRepository $userRepository): Response
    {
        $rgpdForm = $this->createForm(RgpdFormType::class);
        $rgpdForm->handleRequest($request);
        $user = $this->getUser();

        if ($user) {
            if ($rgpdForm->isSubmitted() && $rgpdForm->isValid()) {
                $user->setRgpdAccepted(true);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $download = new Download();
                $download->setUser($user);
                $download->setEbook($ebook);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($download);
                $entityManager->flush();
                return $this->redirectToRoute('ebook_download', ['id' => $ebook->getId()]);
            }
        }

        return $this->render('home/ebook_show.html.twig', [
            'ebook' => $ebook,
            'rgpdForm' => $rgpdForm->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/ebook/{id}/download", name="ebook_download")
     */
    public function downloadEbook(Ebook $ebook, DownloadHandler $downloadHandler, Request $request): Response
    {
        $user = $this->getUser();
        if ($user) {
            if ($user->getIsValidated() == true) {
                return $downloadHandler->downloadObject($ebook, 'documentEbookFile');
            }
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("contact", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, MailerService $mailerService): Response
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            $mailerService->sendEmailAfterContactBeeznessly($contact);
            return $this->render('home/confirmation_message.html.twig');
        }
        return $this->render('home/contact.html.twig', [
            'contact' => $contact,
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
