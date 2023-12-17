<?php

namespace App\Controller;

use Core\Attribute\Route;
use Core\Response\Response;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use Core\Response\RedirectResponse;

class HomeController {

    #[Route(path: '/', name: 'index')]
    public function index(): RedirectResponse {
        return new RedirectResponse('home');
    }

    #[Route(path: '/home', name: 'home')]
    public function home(): Response {
        $UserRepository = new UserRepository();
        $LevelRepository = new LevelRepository();
        $user = $UserRepository->find(2);
        $user
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('johndoe@gmail.com')
            ->setPassword('123456')
            ->setLevel(
                $LevelRepository->find(2)
            );
        $UserRepository->save($user);
        return new Response('home.html.twig');
    }
}
