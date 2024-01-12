<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use Core\Attribute\isGranted;

class AdministrationController extends AbstractController {

    #[isGranted('Administrateur')]
    #[Route('/administration', name: 'administration', methods: ['GET'])]
    public function index(): Response {
        return new Response('administration/index.html.twig');
    }
}
