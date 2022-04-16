<?php

namespace App\Controller;

use App\Entity\Hotel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $hotels = $this->entityManager->getRepository(Hotel::class)->findAll();

        $hotels = array_slice($hotels, 0, 3);
        //dd($hotels);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'hotels' => $hotels
        ]);
    }

    #[Route('mon-compte', name: 'app_my_account')]
    public function my_account(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reservations = [];

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
            'reservations' => $reservations
        ]);
    }
}
