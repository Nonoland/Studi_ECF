<?php

namespace App\Controller;

use App\Entity\Suite;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiteController extends AbstractController
{
    #[Route('/suites', name: 'app_suites')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $suites = $doctrine->getRepository(Suite::class)->findAll();

        return $this->render('suite/index.html.twig', [
            'suites' => $suites,
        ]);
    }

    #[Route('/suite/{slug}', name: 'app_suite')]
    #[Entity('Suite', expr: "repository.find(slug)")]
    public function renderHotel(Suite $suite): Response
    {

        return $this->render('suite/render.html.twig', [
            'suite' => $suite
        ]);
    }
}
