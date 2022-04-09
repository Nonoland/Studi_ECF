<?php

namespace App\Controller;

use App\Entity\Suite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiteController extends AbstractController
{
    #[Route('/suite', name: 'app_suite')]
    public function index(): Response
    {
        return $this->render('suite/index.html.twig', [
            'controller_name' => 'SuiteController',
        ]);
    }

    #[Route('/suite/{slug}', name: 'app_suites')]
    #[Entity('Suite', expr: "repository.find(slug)")]
    public function renderHotel(Suite $suite): Response
    {

        return $this->render('suite/render.html.twig', [
            'suite' => $suite
        ]);
    }
}
