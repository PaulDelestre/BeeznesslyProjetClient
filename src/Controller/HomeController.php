<?php

namespace App\Controller;

use App\Entity\Ebook;
use App\Data\SearchEbooksData;
use App\Form\SearchEbooksType;
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
    public function allExperts(UserRepository $userRepository, Request $request): Response
    {
        $search = new SearchExpertsData();
        $searchForm = $this->createForm(SearchExpertsType::class, $search);
        $searchForm->handleRequest($request);
        $experts = $userRepository->searchExperts($search);
        return $this->render('home/experts.html.twig', [
            'experts' => $experts,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/ebooks", name="home_ebooks")
     */
    public function allEbooks(EbookRepository $ebookRepository, Request $request): Response
    {
        $search = new SearchEbooksData();
        $searchForm = $this->createForm(SearchEbooksType::class, $search);
        $searchForm->handleRequest($request);
        $ebooks = $ebookRepository->searchEbooks($search);
        return $this->render('home/ebooks.html.twig', [
            'ebooks' => $ebooks,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/experts/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="home_expert_show")
     */
    public function showExpert(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('home/expert_show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/ebooks/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="home_ebook_show")
     */
    public function showEbook(int $id, EbookRepository $ebookRepository): Response
    {
        $ebook = $ebookRepository->find($id);

        return $this->render('home/ebook_show.html.twig', [
            'ebook' => $ebook,
        ]);
    }

    /**
     * @Route("/ebook/{id}/download", name="ebook_download")
     */
    public function downloadEbook(Ebook $ebook, DownloadHandler $downloadHandler): Response
    {
        $user = $this->getUser();
        if ($user) {
            if ($user->getIsValidated() == true) {
                $fileName = 'ebook.pdf';
                return $downloadHandler->downloadObject($ebook, 'documentEbookFile', null, $fileName);
            }
        }
        
        return $this->redirectToRoute('app_login');
    }
}
