<?php

namespace App\Controller;

use App\Entity\Hotel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotels', name: 'app_hotels')]
    public function index(): Response
    {
        return $this->render('hotel/index.html.twig', [
            'controller_name' => 'HotelController',
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
}
