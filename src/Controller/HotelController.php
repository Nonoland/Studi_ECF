<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Suite;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotels', name: 'app_hotels')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $hotels = $doctrine->getRepository(Hotel::class)->findAll();

        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotels,
        ]);
    }

    #[Route('/hotel/{slug}', name: 'app_hotel')]
    #[Entity('Hotel', expr: "repository.find(slug)")]
    public function renderHotel(Hotel $hotel): Response
    {
        //dd($hotel);

        return $this->render('hotel/render.html.twig', [
           'hotel' => $hotel
        ]);
    }

    #[Route('/hotel/reservation/{slug_hotel}/{slug_suite}', name: 'app_reservation')]
    public function reservation(ManagerRegistry $doctrine, string $slug_hotel, string $slug_suite): Response
    {
        $hotel = $doctrine->getRepository(Hotel::class)->findOneBy(['slug' => $slug_hotel]);
        $suite = $doctrine->getRepository(Suite::class)->findOneBy(['slug' => $slug_suite]);

        if (!$hotel || !$suite) {
            return $this->redirectToRoute('app_home');
        }

        return new Response();
    }
}
