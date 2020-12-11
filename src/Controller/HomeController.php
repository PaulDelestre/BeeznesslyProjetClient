<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ebook;
use App\Repository\UserRepository;
use App\Repository\EbookRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/experts", name="home_experts")
     */
    public function allExperts(UserRepository $userRepository): Response
    {
        return $this->render('home/experts.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ebooks", name="home_ebooks")
     */
    public function allEbooks(): Response
    {
        $ebooks = $this->getDoctrine()
             ->getRepository(Ebook::class)
             ->findAll();
             
        return $this->render('home/ebooks.html.twig', [
            'ebooks' => $ebooks
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
}
