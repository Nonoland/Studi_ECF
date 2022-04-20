<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Hotel;
use App\Entity\Reservation;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/mon-compte', name: 'app_my_account')]
    public function my_account(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reservations = $this->entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
            'reservations' => $reservations
        ]);
    }

    #[Route('/qui-sommes-nous', name: 'app_about_us')]
    public function about_us()
    {
        return $this->render('home/about_us.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre message a été envoyé !');

            $form = $this->createForm(ContactType::class, new Contact());
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
