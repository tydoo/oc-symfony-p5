<?php

namespace App\Controller;

use App\Route;

class HomeController extends AbstractController {

    #[Route(path: '/', name: 'home')]
    public function index() {
        echo 'Hello world!';
    }
}
