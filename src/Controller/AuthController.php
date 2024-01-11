<?php

namespace App\Controller;

use Core\Security;
use App\Repository\UserRepository;
use Core\Attribute\Route;
use Core\Response\Response;
use Core\AbstractController;
use Core\Response\RedirectResponse;

class AuthController extends AbstractController {

    private Security $security;

    public function __construct() {
        $this->security = new Security();
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(): Response {
        if ($this->security->isLogged) {
            return new RedirectResponse('home');
        }

        $error = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $username = filter_var(htmlspecialchars(strip_tags(trim(addslashes($_POST['username'])))), FILTER_SANITIZE_EMAIL);

            if (!$username) {
                $error = true;
            } else {
                $userReposiotry = new UserRepository();
                $user = $userReposiotry->findOneBy(['email' => $username]);
                if (!$user) {
                    $error = true;
                } else {
                    if (!password_verify($password, $user->getPassword())) {
                        $error = true;
                    } else {
                        session_regenerate_id();
                        $this->security->addSession('user', $user->getId());
                        return new RedirectResponse('home');
                    }
                }
            }
        }

        return new Response('auth/login.html.twig', ['error' => $error]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response {
        session_destroy();
        session_regenerate_id();
        return new RedirectResponse('home');
    }
}
