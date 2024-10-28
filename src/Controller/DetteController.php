<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetteController extends AbstractController
{
    #[Route('/dette', name: 'app_dette')]
    public function index(): Response
    {
        return $this->render('dette/index.html.twig', [
            'controller_name' => 'DetteController',
        ]);
    }
}
