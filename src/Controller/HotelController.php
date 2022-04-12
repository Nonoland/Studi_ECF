<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        return $this->render('hotel/render.html.twig', [
            'hotel' => $hotel,
            'suites' => $hotel->getSuites()->getValues()
        ]);
    }

    #[Route('/hotel/reservation/{slug_hotel}/{slug_suite}', name: 'app_reservation')]
    public function reservation(Request $request, ManagerRegistry $doctrine, string $slug_hotel, string $slug_suite): Response
    {
        $hotel = $doctrine->getRepository(Hotel::class)->findOneBy(['slug' => $slug_hotel]);
        $suite = $doctrine->getRepository(Suite::class)->findOneBy(['slug' => $slug_suite]);

        if (!$hotel || !$suite) {
            return $this->redirectToRoute('app_home');
        }

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $warnings = [];

        $reservation = new Reservation();
        $reservation->setSuite($suite);
        $reservation->setUser(new User((int)$this->getUser()->getId()));
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $reservation Reservation */
            $reservation = $form->getData();

            $date_start = $reservation->getDateStart();
            $date_end = $reservation->getDateEnd();
            $difference = $date_start->diff($date_end);

            if ($difference->d < 3) {
                $warnings[] = 'Votre durée de séjour doit être supérieur au moins de 3 jours.';
            } else {
                $reservation->setSuite($suite);
                $reservation->setUser($this->getUser());

                $this->entityManager->persist($reservation);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_reservation_success', ['id' => $reservation->getId()]);
            }
        }

        return $this->render('hotel/reservation.html.twig', [
            'form' => $form->createView(),
            'suite' => $suite,
            'hotel' => $hotel,
            'warnings' => $warnings
        ]);
    }

    #[Route('/hotel/reservation/success/{id}', name: 'app_reservation_success')]
    public function reservationSuccess(string $id)
    {

        return $this->render('hotel/reservation_success.html.twig');
    }
}
