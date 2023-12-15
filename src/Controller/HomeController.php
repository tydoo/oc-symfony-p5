<?php

namespace App\Controller;

use Core\Route;
use Core\Response\Response;
use Core\Response\RedirectResponse;

class HomeController {

    #[Route(path: '/', name: 'index')]
    public function index(): RedirectResponse {
        return new RedirectResponse('home');
    }

    #[Route(path: '/home', name: 'home')]
    public function home(): Response {
        return new Response('home.html.twig');
    }
}
