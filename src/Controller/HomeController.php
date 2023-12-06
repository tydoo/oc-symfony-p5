<?php

namespace App\Controller;

use App\Route;

class HomeController extends AbstractController {

    #[Route(path: '/', name: 'index')]
    public function index() {
        return $this->redirectToRoute('home');
    }

    #[Route(path: '/home', name: 'home')]
    public function home() {
        return $this->response('home.html.twig');
    }
}
