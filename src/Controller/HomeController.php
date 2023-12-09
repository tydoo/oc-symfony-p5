<?php

namespace App\Controller;

use App\RedirectResponse;
use App\Response;
use App\Route;

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
